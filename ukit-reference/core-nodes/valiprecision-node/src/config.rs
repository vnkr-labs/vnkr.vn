/// config.rs — Node configuration
use serde::{Deserialize, Serialize};
use std::path::Path;

#[derive(Debug, Clone, Serialize, Deserialize)]
pub struct NodeConfig {
    pub node_id: String,
    pub network: String,       // "localnet" | "devnet" | "testnet" | "mainnet"
    pub data_dir: String,
    pub metrics_port: u16,
    pub bootstrap_peers: Vec<String>,
    pub validator_keypair_path: String,
    pub rpc_port: u16,
    pub target_tps: u64,
}

impl Default for NodeConfig {
    fn default() -> Self {
        Self {
            node_id: "vpx-node-0".into(),
            network: "localnet".into(),
            data_dir: "./data/vpx".into(),
            metrics_port: 9090,
            bootstrap_peers: vec![],
            validator_keypair_path: "./keys/validator.json".into(),
            rpc_port: 8899,
            target_tps: 600_000,
        }
    }
}

impl NodeConfig {
    pub fn load(path: &str) -> anyhow::Result<Self> {
        if Path::new(path).exists() {
            let content = std::fs::read_to_string(path)?;
            Ok(toml::from_str(&content)?)
        } else {
            tracing::warn!(path, "Config file not found — using defaults");
            Ok(Self::default())
        }
    }
}
