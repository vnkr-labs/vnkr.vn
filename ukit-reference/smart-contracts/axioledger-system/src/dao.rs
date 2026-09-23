/// dao.rs — Quadratic Voting + Guardian Council + TimeLock
/// Governance Architecture: Tam Quyền Phân Lập
///   - Lập pháp : Quadratic Voting (votes = sqrt(tokens))
///   - Tư pháp  : Guardian Council (5 ghế, Veto 4/5) trong Objection Window
///   - Hành pháp: Time-Lock (7 ngày) + Escape Hatch

use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{
    account_info::AccountInfo,
    clock::Clock,
    msg,
    program_error::ProgramError,
    pubkey::Pubkey,
    sysvar::Sysvar,
};

// ─── Constants ───────────────────────────────────────────────────────────────
pub const GUARDIAN_SEATS: usize = 5;
pub const VETO_THRESHOLD: usize = 4;            // 4/5 guardians to veto
pub const TIMELOCK_SECONDS: i64 = 7 * 24 * 3600; // 7 days
pub const OBJECTION_WINDOW_SECONDS: i64 = 3 * 24 * 3600; // 3 days

// ─── State ───────────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct Proposal {
    pub id: u64,
    pub proposer: Pubkey,
    pub description_hash: [u8; 32],   // SHA-256 of off-chain description (IPFS CID)
    pub votes_for_sqrt: u64,           // Σ sqrt(token_balance) per voter
    pub votes_against_sqrt: u64,
    pub created_at: i64,
    pub execution_time: i64,           // created_at + TIMELOCK_SECONDS
    pub status: ProposalStatus,
    pub veto_count: u8,
}

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone, PartialEq)]
pub enum ProposalStatus {
    Active,
    Passed,
    Vetoed,
    Executed,
    Cancelled,
}

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct GuardianCouncil {
    pub members: [Pubkey; GUARDIAN_SEATS],
    pub veto_votes: [bool; GUARDIAN_SEATS],
}

// ─── Instructions ────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum GovernanceInstruction {
    CreateProposal { description_hash: [u8; 32] },
    CastVote       { proposal_id: u64, token_balance: u64, approve: bool },
    VetoProposal   { proposal_id: u64 },
    ExecuteProposal{ proposal_id: u64 },
    EscapeHatch    { proposal_id: u64 },
}

impl GovernanceInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data)
            .map_err(|_| ProgramError::InvalidInstructionData)
    }
}

// ─── Processor ───────────────────────────────────────────────────────────────
pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    instruction: GovernanceInstruction,
) -> solana_program::entrypoint::ProgramResult {
    match instruction {
        GovernanceInstruction::CreateProposal { description_hash } => {
            msg!("DAO: Creating proposal hash={:?}", description_hash);
            // TODO: deserialize accounts, write Proposal state
            Ok(())
        }
        GovernanceInstruction::CastVote { proposal_id, token_balance, approve } => {
            let weight = integer_sqrt(token_balance); // Quadratic Voting
            msg!("DAO: Vote on #{} weight={} approve={}", proposal_id, weight, approve);
            // TODO: update proposal vote tallies
            Ok(())
        }
        GovernanceInstruction::VetoProposal { proposal_id } => {
            msg!("DAO: Guardian veto on proposal #{}", proposal_id);
            // TODO: verify signer is guardian, increment veto_count
            Ok(())
        }
        GovernanceInstruction::ExecuteProposal { proposal_id } => {
            let clock = Clock::get()?;
            msg!("DAO: Execute proposal #{} at ts={}", proposal_id, clock.unix_timestamp);
            // TODO: verify timelock elapsed, status == Passed, veto_count < VETO_THRESHOLD
            Ok(())
        }
        GovernanceInstruction::EscapeHatch { proposal_id } => {
            msg!("DAO: Escape hatch triggered for proposal #{}", proposal_id);
            // TODO: emergency withdrawal logic
            Ok(())
        }
    }
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
/// Integer square root (Quadratic Voting weight)
/// votes = floor(sqrt(token_balance))
pub fn integer_sqrt(n: u64) -> u64 {
    if n == 0 { return 0; }
    let mut x = n;
    let mut y = (x + 1) / 2;
    while y < x {
        x = y;
        y = (x + n / x) / 2;
    }
    x
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_quadratic_voting_weight() {
        assert_eq!(integer_sqrt(0), 0);
        assert_eq!(integer_sqrt(1), 1);
        assert_eq!(integer_sqrt(4), 2);
        assert_eq!(integer_sqrt(9), 3);
        assert_eq!(integer_sqrt(100), 10);
        assert_eq!(integer_sqrt(1_000_000), 1_000);
        // Whale with 1 billion tokens only gets 31623 votes
        assert_eq!(integer_sqrt(1_000_000_000), 31_622);
    }

    #[test]
    fn test_veto_threshold() {
        assert_eq!(VETO_THRESHOLD, 4);
        assert_eq!(GUARDIAN_SEATS, 5);
        // Requires 4 of 5 guardians to veto
        assert!(VETO_THRESHOLD < GUARDIAN_SEATS);
    }
}
