/// valiprecision-node/src/main.rs
/// $VPX Node Entry Point — Consensus Engine + P2P Network
/// Target: ~120,000 LOC | License: BSL 1.1

mod consensus;
mod network;
mod storage;
mod metrics;
mod config;

use clap::Parser;
use tracing::{info, warn};
use tracing_subscriber::EnvFilter;

#[derive(Parser, Debug)]
#[command(name = "vpx-node", version = "0.1.0", about = "Valiprecision Consensus Node")]
pub struct Args {
    /// Path to node config file
    #[arg(short, long, default_value = "config/vpx-node.toml")]
    config: String,

    /// Override P2P listen address
    #[arg(long, default_value = "/ip4/0.0.0.0/tcp/9000")]
    listen_addr: String,

    /// Target TPS for stress testing
    #[arg(long, default_value_t = 600_000)]
    target_tps: u64,

    /// Enable metrics exporter
    #[arg(long, default_value_t = true)]
    metrics: bool,
}

#[tokio::main]
async fn main() -> anyhow::Result<()> {
    // Initialize structured logging
    tracing_subscriber::fmt()
        .with_env_filter(EnvFilter::from_default_env()
            .add_directive("vpx_node=info".parse()?)
            .add_directive("libp2p=warn".parse()?))
        .json()
        .init();

    let args = Args::parse();
    info!(version = "0.1.0", config = %args.config, "Valiprecision node starting");

    // Load configuration
    let cfg = config::NodeConfig::load(&args.config)?;
    info!(
        node_id = %cfg.node_id,
        network = %cfg.network,
        "Configuration loaded"
    );

    // Initialize storage (RocksDB)
    let db = storage::BlockStore::open(&cfg.data_dir)?;
    info!(data_dir = %cfg.data_dir, "Block store opened");

    // Start P2P network
    let (network_tx, network_rx) = tokio::sync::mpsc::channel(1024);
    let network_handle = tokio::spawn(
        network::P2PNetwork::new(args.listen_addr.parse()?, cfg.bootstrap_peers.clone())
            .start(network_tx)
    );
    info!("P2P network started on {}", args.listen_addr);

    // Start consensus engine
    let consensus_handle = tokio::spawn(
        consensus::ConsensusEngine::new(cfg.clone(), db, network_rx)
            .run()
    );
    info!("Consensus engine started (target {} TPS)", args.target_tps);

    // Start metrics if enabled
    if args.metrics {
        let metrics_handle = tokio::spawn(metrics::MetricsServer::start(cfg.metrics_port));
        info!(port = cfg.metrics_port, "Metrics server started");
        metrics_handle.await??;
    }

    // Await shutdown
    tokio::select! {
        res = network_handle   => { warn!("P2P network exited: {:?}", res); }
        res = consensus_handle => { warn!("Consensus engine exited: {:?}", res); }
    }

    Ok(())
}
