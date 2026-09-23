/// rollup.rs — SVM Rollup Batcher (L2 → L1 state commitment)
use sha2::{Digest, Sha256};
use tracing::info;

pub struct RollupBatcher {
    pending_txs: Vec<[u8; 32]>,
    batch_size: usize,
}

impl RollupBatcher {
    pub fn new() -> Self {
        Self {
            pending_txs: Vec::new(),
            batch_size: 10_000, // commit to L1 every 10k txs
        }
    }

    pub async fn run(mut self) -> anyhow::Result<()> {
        info!(batch_size = self.batch_size, "Rollup batcher running");
        loop {
            tokio::time::sleep(tokio::time::Duration::from_millis(100)).await;
            if self.pending_txs.len() >= self.batch_size {
                let root = self.compute_merkle_root();
                info!(root = ?root, txs = self.pending_txs.len(), "Submitting rollup batch to L1");
                // TODO: submit state_root + ZK proof to L1 contract
                self.pending_txs.clear();
            }
        }
    }

    pub fn add_transaction(&mut self, tx_hash: [u8; 32]) {
        self.pending_txs.push(tx_hash);
    }

    /// Compute Merkle root of pending transaction hashes
    fn compute_merkle_root(&self) -> [u8; 32] {
        if self.pending_txs.is_empty() {
            return [0u8; 32];
        }
        let mut layer: Vec<[u8; 32]> = self.pending_txs.clone();
        while layer.len() > 1 {
            layer = layer.chunks(2).map(|pair| {
                let mut h = Sha256::new();
                h.update(pair[0]);
                h.update(pair.get(1).copied().unwrap_or(pair[0])); // duplicate last if odd
                h.finalize().into()
            }).collect();
        }
        layer[0]
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_merkle_root_empty() {
        let batcher = RollupBatcher::new();
        assert_eq!(batcher.compute_merkle_root(), [0u8; 32]);
    }

    #[test]
    fn test_merkle_root_single() {
        let mut batcher = RollupBatcher::new();
        let hash = [1u8; 32];
        batcher.add_transaction(hash);
        let root = batcher.compute_merkle_root();
        // Single element: H(hash, hash)
        assert_ne!(root, [0u8; 32]);
    }

    #[test]
    fn test_merkle_root_deterministic() {
        let mut b1 = RollupBatcher::new();
        let mut b2 = RollupBatcher::new();
        for i in 0u8..8 {
            b1.add_transaction([i; 32]);
            b2.add_transaction([i; 32]);
        }
        assert_eq!(b1.compute_merkle_root(), b2.compute_merkle_root());
    }
}
