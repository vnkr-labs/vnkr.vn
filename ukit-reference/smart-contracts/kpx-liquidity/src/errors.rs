/// errors.rs — KPX error types
use thiserror::Error;
use solana_program::program_error::ProgramError;

#[derive(Error, Debug, Clone, PartialEq)]
pub enum KPXError {
    #[error("Insufficient liquidity in pool")]
    InsufficientLiquidity,
    #[error("Slippage tolerance exceeded")]
    SlippageExceeded,
    #[error("Invalid ZK bridge proof")]
    InvalidBridgeProof,
    #[error("Bridge nonce already used (replay attack)")]
    NonceReplay,
    #[error("RWA asset not verified by legal authority")]
    UnverifiedRWAAsset,
    #[error("Arithmetic overflow in swap calculation")]
    ArithmeticOverflow,
    #[error("Pool not initialized")]
    PoolNotInitialized,
}

impl From<KPXError> for ProgramError {
    fn from(e: KPXError) -> Self {
        ProgramError::Custom(e as u32)
    }
}
