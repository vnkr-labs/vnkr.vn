/// sequentichain-node/src/main.rs
/// $SQX L2 Execution Engine — AF_XDP + SVM Rollup
/// Target: ~150,000 LOC | License: BSL 1.1

mod executor;
mod af_xdp;
mod rollup;
mod sequencer;
mod state;
mod config;
mod rpc;

use clap::Parser;
use tracing::{info, warn};
use tracing_subscriber::EnvFilter;

#[derive(Parser, Debug)]
#[command(name = "sqx-node", version = "0.1.0", about = "Sequentichain L2 Execution Node")]
pub struct Args {
    #[arg(short, long, default_value = "config/sqx-node.toml")]
    config: String,

    /// Network interface for AF_XDP bypass
    #[arg(long, default_value = "eth0")]
    nic_interface: String,

    /// RPC listen port
    #[arg(long, default_value_t = 8545)]
    rpc_port: u16,

    /// Target throughput
    #[arg(long, default_value_t = 600_000)]
    target_tps: u64,

    /// Enable RAMDISK I/O optimization
    #[arg(long, default_value_t = true)]
    ramdisk: bool,
}

#[tokio::main]
async fn main() -> anyhow::Result<()> {
    tracing_subscriber::fmt()
        .with_env_filter(EnvFilter::from_default_env()
            .add_directive("sqx_node=info".parse()?))
        .json()
        .init();

    let args = Args::parse();
    info!(version = "0.1.0", nic = %args.nic_interface, "Sequentichain node starting");

    // Initialize AF_XDP bypass (kernel bypass for maximum NIC throughput)
    let xdp = if cfg!(target_os = "linux") {
        info!("AF_XDP: Attempting kernel bypass on {}", args.nic_interface);
        af_xdp::AfXdpSocket::new(&args.nic_interface).ok()
    } else {
        warn!("AF_XDP: Only supported on Linux — falling back to standard I/O");
        None
    };

    // Initialize state DB (RAMDISK-backed on Linux)
    let state_dir = if args.ramdisk && cfg!(target_os = "linux") {
        "/dev/shm/sqx-state".to_string()
    } else {
        "./data/sqx-state".to_string()
    };
    let state = state::StateDB::open(&state_dir)?;
    info!(state_dir, "State database opened");

    // Start sequencer
    let sequencer_handle = tokio::spawn(
        sequencer::Sequencer::new(state, args.target_tps).run()
    );

    // Start rollup batcher
    let rollup_handle = tokio::spawn(
        rollup::RollupBatcher::new().run()
    );

    // Start JSON-RPC server
    let rpc_handle = tokio::spawn(
        rpc::RpcServer::start(args.rpc_port)
    );

    info!(tps = args.target_tps, "All subsystems started");

    tokio::select! {
        res = sequencer_handle => { warn!("Sequencer exited: {:?}", res); }
        res = rollup_handle    => { warn!("Rollup batcher exited: {:?}", res); }
        res = rpc_handle       => { warn!("RPC server exited: {:?}", res); }
    }

    Ok(())
}
