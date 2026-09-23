/// metrics.rs — Prometheus metrics exporter
use tracing::info;

pub struct MetricsServer;

impl MetricsServer {
    pub async fn start(port: u16) -> anyhow::Result<()> {
        info!(port, "Metrics server listening on :{}/metrics", port);
        // TODO: integrate with prometheus crate
        // Expose: vpx_current_slot, vpx_tps_gauge, vpx_validator_count,
        //         vpx_nakamoto_coefficient, vpx_block_finalization_ms
        loop {
            tokio::time::sleep(tokio::time::Duration::from_secs(15)).await;
        }
    }
}
