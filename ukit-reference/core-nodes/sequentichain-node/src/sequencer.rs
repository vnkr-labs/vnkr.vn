/// sequencer.rs — Transaction Sequencer (parallel execution via Rayon)
use rayon::prelude::*;
use tracing::{debug, info};

use crate::state::StateDB;

pub struct Sequencer {
    state: StateDB,
    target_tps: u64,
}

impl Sequencer {
    pub fn new(state: StateDB, target_tps: u64) -> Self {
        Self { state, target_tps }
    }

    pub async fn run(self) -> anyhow::Result<()> {
        info!(target_tps = self.target_tps, "Sequencer running");
        // TODO: 
        // 1. Receive tx batches from network
        // 2. Validate signatures in parallel (rayon par_iter)
        // 3. Detect conflicting accounts (parallel-safe scheduling)
        // 4. Execute non-conflicting txs concurrently
        // 5. Apply state changes atomically
        // 6. Emit batch to RollupBatcher
        loop {
            tokio::time::sleep(tokio::time::Duration::from_millis(1)).await;
        }
    }

    /// Execute a batch of transactions in parallel (rayon)
    pub fn execute_batch_parallel(txs: &[RawTransaction]) -> Vec<ExecutionResult> {
        txs.par_iter()
           .map(|tx| Self::execute_single(tx))
           .collect()
    }

    fn execute_single(tx: &RawTransaction) -> ExecutionResult {
        // TODO: SVM bytecode execution
        debug!(id = ?tx.id, "Executing transaction");
        ExecutionResult { id: tx.id, success: true, gas_used: 0 }
    }
}

#[derive(Debug, Clone)]
pub struct RawTransaction {
    pub id: [u8; 32],
    pub data: Vec<u8>,
    pub fee: u64,
}

#[derive(Debug, Clone)]
pub struct ExecutionResult {
    pub id: [u8; 32],
    pub success: bool,
    pub gas_used: u64,
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_parallel_batch_execution() {
        let txs: Vec<RawTransaction> = (0..1000u64)
            .map(|i| {
                let mut id = [0u8; 32];
                id[..8].copy_from_slice(&i.to_le_bytes());
                RawTransaction { id, data: vec![], fee: 1 }
            })
            .collect();

        let results = Sequencer::execute_batch_parallel(&txs);
        assert_eq!(results.len(), 1000);
        assert!(results.iter().all(|r| r.success));
    }
}
