/// errors.rs — AXQ System Error types
use thiserror::Error;
use solana_program::program_error::ProgramError;

#[derive(Error, Debug, Clone, PartialEq)]
pub enum AXQError {
    #[error("Unauthorized: signer is not a valid authority")]
    Unauthorized,
    #[error("Proposal not found or invalid ID")]
    InvalidProposal,
    #[error("TimeLock has not elapsed yet")]
    TimeLockActive,
    #[error("Veto threshold reached — proposal is blocked")]
    Vetoed,
    #[error("Allocation cap exceeded")]
    AllocationCapExceeded,
    #[error("Treasury is frozen")]
    TreasuryFrozen,
    #[error("Arithmetic overflow in token calculation")]
    ArithmeticOverflow,
    #[error("Invalid namespace — reserved or malicious")]
    InvalidNamespace,
}

impl From<AXQError> for ProgramError {
    fn from(e: AXQError) -> Self {
        ProgramError::Custom(e as u32)
    }
}
