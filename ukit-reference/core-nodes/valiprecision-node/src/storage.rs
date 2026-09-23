/// storage.rs — RocksDB block store
use tracing::info;

use crate::consensus::Block;

pub struct BlockStore {
    pub data_dir: String,
}

impl BlockStore {
    pub fn open(data_dir: &str) -> anyhow::Result<Self> {
        std::fs::create_dir_all(data_dir)?;
        info!(data_dir, "BlockStore opened");
        // TODO: initialize RocksDB with column families: blocks, votes, tx_index
        Ok(Self { data_dir: data_dir.to_string() })
    }

    pub fn write_block(&self, block: &Block) -> anyhow::Result<()> {
        // TODO: serialize block with bincode/serde_json, store by slot key
        info!(slot = block.slot, "Block written to store");
        Ok(())
    }

    pub fn get_block(&self, slot: u64) -> anyhow::Result<Option<Block>> {
        // TODO: RocksDB get by slot key
        let _ = slot;
        Ok(None)
    }

    pub fn latest_slot(&self) -> anyhow::Result<u64> {
        // TODO: RocksDB iterator last key
        Ok(0)
    }
}
