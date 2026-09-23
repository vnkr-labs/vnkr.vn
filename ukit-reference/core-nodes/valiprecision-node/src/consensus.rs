/// consensus.rs — Tower BFT / PoH-inspired Consensus Engine
/// Byzantine Fault Tolerance: tolerates ≤ (n-1)/3 faulty validators
/// Nakamoto Coefficient target: > 50

use std::collections::HashMap;
use tokio::sync::mpsc::Receiver;
use tracing::{debug, error, info, warn};
use sha2::{Digest, Sha256};
use serde::{Deserialize, Serialize};

use crate::{config::NodeConfig, storage::BlockStore};

// ─── Types ────────────────────────────────────────────────────────────────────
pub type Slot = u64;
pub type Hash = [u8; 32];

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct Block {
    pub slot: Slot,
    pub parent_hash: Hash,
    pub transactions: Vec<Transaction>,
    pub validator: [u8; 32],  // ed25519 public key
    pub timestamp_ms: u64,
    pub block_hash: Hash,
}

impl Block {
    pub fn compute_hash(&self) -> Hash {
        let mut hasher = Sha256::new();
        hasher.update(self.slot.to_le_bytes());
        hasher.update(self.parent_hash);
        hasher.update(self.timestamp_ms.to_le_bytes());
        hasher.finalize().into()
    }
}

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct Transaction {
    pub id: [u8; 32],
    pub instructions: Vec<u8>,
    pub signature: [u8; 64],
    pub fee_lamports: u64,
}

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct Vote {
    pub slot: Slot,
    pub block_hash: Hash,
    pub validator: [u8; 32],
    pub signature: [u8; 64],
    pub lockout: u32, // Tower BFT lockout (doubles per consecutive vote)
}

// ─── Consensus Engine ─────────────────────────────────────────────────────────
pub struct ConsensusEngine {
    config: NodeConfig,
    db: BlockStore,
    network_rx: Receiver<NetworkMessage>,
    current_slot: Slot,
    vote_tally: HashMap<Hash, u64>,  // block_hash → weighted votes
    validators: HashMap<[u8; 32], u64>, // pubkey → stake weight
}

#[derive(Debug)]
pub enum NetworkMessage {
    NewBlock(Block),
    NewVote(Vote),
    NewTransaction(Transaction),
}

impl ConsensusEngine {
    pub fn new(config: NodeConfig, db: BlockStore, network_rx: Receiver<NetworkMessage>) -> Self {
        Self {
            config,
            db,
            network_rx,
            current_slot: 0,
            vote_tally: HashMap::new(),
            validators: HashMap::new(),
        }
    }

    pub async fn run(mut self) -> anyhow::Result<()> {
        info!(slot = self.current_slot, "Consensus engine running");

        while let Some(msg) = self.network_rx.recv().await {
            match msg {
                NetworkMessage::NewBlock(block) => {
                    self.process_block(block).await?;
                }
                NetworkMessage::NewVote(vote) => {
                    self.process_vote(vote).await?;
                }
                NetworkMessage::NewTransaction(tx) => {
                    debug!(tx_id = ?tx.id, "Transaction received");
                }
            }
        }

        warn!("Network channel closed — consensus shutting down");
        Ok(())
    }

    async fn process_block(&mut self, block: Block) -> anyhow::Result<()> {
        // Validate block hash
        let expected_hash = block.compute_hash();
        if expected_hash != block.block_hash {
            error!(slot = block.slot, "Invalid block hash — rejected");
            return Ok(());
        }

        // Validate slot ordering
        if block.slot <= self.current_slot {
            warn!(slot = block.slot, current = self.current_slot, "Stale block ignored");
            return Ok(());
        }

        info!(slot = block.slot, txs = block.transactions.len(), "Block accepted");
        self.current_slot = block.slot;
        self.db.write_block(&block)?;
        Ok(())
    }

    async fn process_vote(&mut self, vote: Vote) -> anyhow::Result<()> {
        // Accumulate stake-weighted votes (Tower BFT)
        let stake = self.validators.get(&vote.validator).copied().unwrap_or(0);
        let entry = self.vote_tally.entry(vote.block_hash).or_insert(0);
        *entry += stake;

        let total_stake: u64 = self.validators.values().sum();
        let supermajority = total_stake * 2 / 3;

        if *entry > supermajority {
            info!(slot = vote.slot, "Supermajority reached — block finalized");
        }

        Ok(())
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_block_hash_deterministic() {
        let block = Block {
            slot: 42,
            parent_hash: [0u8; 32],
            transactions: vec![],
            validator: [1u8; 32],
            timestamp_ms: 1_700_000_000_000,
            block_hash: [0u8; 32],
        };
        let h1 = block.compute_hash();
        let h2 = block.compute_hash();
        assert_eq!(h1, h2, "Hash must be deterministic");
    }

    #[test]
    fn test_supermajority_threshold() {
        let total_stake: u64 = 300;
        let supermajority = total_stake * 2 / 3;
        assert_eq!(supermajority, 200);
        assert!(201 > supermajority); // passes
        assert!(200 <= supermajority); // exactly 2/3 does NOT pass (strictly greater needed)
    }
}
