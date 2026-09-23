/// network.rs — P2P Network (libp2p: GossipSub + Kademlia DHT)
use libp2p::{Multiaddr, PeerId};
use tokio::sync::mpsc::Sender;
use tracing::{info, warn};

use crate::consensus::NetworkMessage;

pub struct P2PNetwork {
    listen_addr: Multiaddr,
    bootstrap_peers: Vec<String>,
}

impl P2PNetwork {
    pub fn new(listen_addr: Multiaddr, bootstrap_peers: Vec<String>) -> Self {
        Self { listen_addr, bootstrap_peers }
    }

    pub async fn start(self, _tx: Sender<NetworkMessage>) -> anyhow::Result<()> {
        info!(addr = %self.listen_addr, peers = self.bootstrap_peers.len(), "P2P starting");
        // TODO: Initialize libp2p swarm with GossipSub + Kademlia
        // TODO: Connect to bootstrap peers
        // TODO: Subscribe to block/vote/tx topics
        // TODO: Forward incoming messages to consensus engine via tx
        warn!("P2P network: stub implementation — TODO: full libp2p integration");
        // Keep alive
        loop {
            tokio::time::sleep(tokio::time::Duration::from_secs(60)).await;
        }
    }
}
