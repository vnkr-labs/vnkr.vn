/// registry.rs — Domain registration logic
use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{
    account_info::AccountInfo, clock::Clock, msg,
    program_error::ProgramError, pubkey::Pubkey, sysvar::Sysvar,
};

// ─── Supported TLDs ──────────────────────────────────────────────────────────
pub const VALID_TLDS: &[&str] = &[".axq", ".vpx", ".sqx", ".kpx", ".vrq"];
pub const MAX_LABEL_LEN: usize = 63;
pub const MIN_LABEL_LEN: usize = 3;
pub const REGISTRATION_PERIOD_SECONDS: i64 = 365 * 24 * 3600; // 1 year

// ─── State ───────────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct DomainRecord {
    pub label: [u8; 64],          // e.g. b"alice\0..."
    pub tld: [u8; 8],             // e.g. b".axq\0..."
    pub owner: Pubkey,
    pub resolver: Pubkey,         // Points to ZK-DID or wallet address
    pub registered_at: i64,
    pub expires_at: i64,
    pub is_active: bool,
}

// ─── Instructions ────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub struct RegisterInstruction {
    pub label: String,
    pub tld: String,
    pub resolver: Pubkey,
}

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub struct TransferInstruction {
    pub label: String,
    pub tld: String,
    pub new_owner: Pubkey,
}

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub struct RevokeInstruction {
    pub label: String,
    pub tld: String,
}

impl RegisterInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}
impl TransferInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}
impl RevokeInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}

// ─── Processors ──────────────────────────────────────────────────────────────
pub fn process_register(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    ix: RegisterInstruction,
) -> solana_program::entrypoint::ProgramResult {
    // Validate label length
    let label = ix.label.trim();
    if label.len() < MIN_LABEL_LEN || label.len() > MAX_LABEL_LEN {
        return Err(ProgramError::InvalidArgument);
    }
    // Validate TLD
    if !VALID_TLDS.contains(&ix.tld.as_str()) {
        msg!("ANS: Invalid TLD '{}'", ix.tld);
        return Err(ProgramError::InvalidArgument);
    }
    let clock = Clock::get()?;
    msg!("ANS: Registering '{}{}'  resolver={:?}", label, ix.tld, ix.resolver);
    msg!("ANS: Expires at ts={}", clock.unix_timestamp + REGISTRATION_PERIOD_SECONDS);
    Ok(())
}

pub fn process_transfer(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    ix: TransferInstruction,
) -> solana_program::entrypoint::ProgramResult {
    msg!("ANS: Transfer '{}{}' -> new_owner={:?}", ix.label, ix.tld, ix.new_owner);
    Ok(())
}

pub fn process_revoke(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    ix: RevokeInstruction,
) -> solana_program::entrypoint::ProgramResult {
    msg!("ANS: Revoke '{}{}'", ix.label, ix.tld);
    Ok(())
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_valid_tlds() {
        for tld in VALID_TLDS {
            assert!(tld.starts_with('.'));
        }
        assert_eq!(VALID_TLDS.len(), 5);
    }

    #[test]
    fn test_label_length_bounds() {
        assert!(MIN_LABEL_LEN <= MAX_LABEL_LEN);
        // "ab" is too short
        assert!("ab".len() < MIN_LABEL_LEN);
        // "abc" is valid minimum
        assert!("abc".len() >= MIN_LABEL_LEN);
    }
}
