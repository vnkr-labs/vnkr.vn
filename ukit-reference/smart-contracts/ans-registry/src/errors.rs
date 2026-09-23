/// errors.rs — ANS error types
use thiserror::Error;
use solana_program::program_error::ProgramError;

#[derive(Error, Debug, Clone, PartialEq)]
pub enum ANSError {
    #[error("Domain already registered")]
    AlreadyRegistered,
    #[error("Domain not found")]
    NotFound,
    #[error("Domain expired")]
    Expired,
    #[error("Invalid TLD — must be one of .axq .vpx .sqx .kpx .vrq")]
    InvalidTLD,
    #[error("Label length out of bounds (3–63 chars)")]
    InvalidLabelLength,
    #[error("Label blocked by Security Guard")]
    BlockedBySecurityGuard,
    #[error("Unauthorized transfer — signer is not domain owner")]
    UnauthorizedTransfer,
}

impl From<ANSError> for ProgramError {
    fn from(e: ANSError) -> Self {
        ProgramError::Custom(e as u32)
    }
}
