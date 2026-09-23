/// config.rs — SQX node configuration
use serde::{Deserialize, Serialize};
use std::path::Path;

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct SqxConfig {
    pub node_id: String,
    pub network: String,
    pub data_dir: String,
    pub nic_interface: String,
    pub rpc_port: u16,
    pub target_tps: u64,
    pub use_ramdisk: bool,
    pub l1_rpc_url: String,
    pub rollup_batch_size: usize,
}

impl Default for SqxConfig {
    fn default() -> Self {
        Self {
            node_id: "sqx-node-0".into(),
            network: "localnet".into(),
            data_dir: "./data/sqx".into(),
            nic_interface: "eth0".into(),
            rpc_port: 8545,
            target_tps: 600_000,
            use_ramdisk: true,
            l1_rpc_url: "http://127.0.0.1:8899".into(),
            rollup_batch_size: 10_000,
        }
    }
}

impl SqxConfig {
    pub fn load(path: &str) -> anyhow::Result<Self> {
        if Path::new(path).exists() {
            let content = std::fs::read_to_string(path)?;
            Ok(toml::from_str(&content)?)
        } else {
            Ok(Self::default())
        }
    }
}
