/// rpc.rs — JSON-RPC server for L2
use tracing::info;

pub struct RpcServer;

impl RpcServer {
    pub async fn start(port: u16) -> anyhow::Result<()> {
        info!(port, "JSON-RPC server listening on 0.0.0.0:{}", port);
        // TODO: implement JSON-RPC methods:
        //   sqx_sendTransaction
        //   sqx_getTransaction
        //   sqx_getSlot
        //   sqx_getBalance
        //   sqx_getAccountInfo
        //   sqx_simulateTransaction
        loop {
            tokio::time::sleep(tokio::time::Duration::from_secs(60)).await;
        }
    }
}
