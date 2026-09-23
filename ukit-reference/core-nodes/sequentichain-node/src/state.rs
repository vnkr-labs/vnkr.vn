/// state.rs — L2 State Database (RocksDB or RAMDISK-backed)
use tracing::info;

pub struct StateDB {
    pub path: String,
}

impl StateDB {
    pub fn open(path: &str) -> anyhow::Result<Self> {
        std::fs::create_dir_all(path)?;
        info!(path, "StateDB opened");
        // TODO: RocksDB with column families: accounts, programs, slots
        Ok(Self { path: path.to_string() })
    }

    pub fn get_account(&self, pubkey: &[u8; 32]) -> anyhow::Result<Option<AccountState>> {
        let _ = pubkey;
        // TODO: RocksDB get
        Ok(None)
    }

    pub fn set_account(&self, pubkey: &[u8; 32], state: &AccountState) -> anyhow::Result<()> {
        let _ = (pubkey, state);
        // TODO: RocksDB put with write batch
        Ok(())
    }

    pub fn commit_batch(&self, changes: &[([u8; 32], AccountState)]) -> anyhow::Result<()> {
        for (key, val) in changes {
            self.set_account(key, val)?;
        }
        info!(count = changes.len(), "State batch committed");
        Ok(())
    }
}

#[derive(Debug, Clone)]
pub struct AccountState {
    pub lamports: u64,
    pub data: Vec<u8>,
    pub owner: [u8; 32],
    pub executable: bool,
}
