/// tokenomics.rs — 500 Billion $AXQ Distribution
/// Total Supply: 500,000,000,000 AXQ
///
/// Allocation:
///   VPX Subsidy  25% = 125,000,000,000
///   R&D          30% = 150,000,000,000
///   RWA Treasury 15% =  75,000,000,000
///   Team         12% =  60,000,000,000
///   Strategic    13% =  65,000,000,000
///   TGE           5% =  25,000,000,000

use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{
    account_info::AccountInfo, msg, program_error::ProgramError, pubkey::Pubkey,
};

// ─── Constants ───────────────────────────────────────────────────────────────
pub const TOTAL_SUPPLY: u64 = 500_000_000_000;

pub const ALLOCATION_VPX_SUBSIDY:  u64 = 125_000_000_000; // 25%
pub const ALLOCATION_RND:          u64 = 150_000_000_000; // 30%
pub const ALLOCATION_RWA_TREASURY: u64 =  75_000_000_000; // 15%
pub const ALLOCATION_TEAM:         u64 =  60_000_000_000; // 12%
pub const ALLOCATION_STRATEGIC:    u64 =  65_000_000_000; // 13%
pub const ALLOCATION_TGE:          u64 =  25_000_000_000; //  5%

// ─── State ───────────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct TokenomicsState {
    pub total_minted: u64,
    pub vpx_subsidy_distributed: u64,
    pub rnd_distributed: u64,
    pub rwa_treasury_locked: u64,
    pub team_vested: u64,
    pub strategic_distributed: u64,
    pub tge_released: u64,
}

impl TokenomicsState {
    pub fn remaining_supply(&self) -> u64 {
        TOTAL_SUPPLY.saturating_sub(self.total_minted)
    }

    pub fn verify_allocations(&self) -> bool {
        let sum = ALLOCATION_VPX_SUBSIDY
            + ALLOCATION_RND
            + ALLOCATION_RWA_TREASURY
            + ALLOCATION_TEAM
            + ALLOCATION_STRATEGIC
            + ALLOCATION_TGE;
        sum == TOTAL_SUPPLY
    }
}

// ─── Instructions ────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum TokenomicsInstruction {
    InitializeSupply,
    ReleaseTGE { amount: u64 },
    VestTeam    { recipient: Pubkey, amount: u64 },
    SubsidizeVPX{ amount: u64 },
}

impl TokenomicsInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}

// ─── Processor ───────────────────────────────────────────────────────────────
pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    instruction: TokenomicsInstruction,
) -> solana_program::entrypoint::ProgramResult {
    match instruction {
        TokenomicsInstruction::InitializeSupply => {
            msg!("Tokenomics: Initializing {} AXQ total supply", TOTAL_SUPPLY);
            Ok(())
        }
        TokenomicsInstruction::ReleaseTGE { amount } => {
            assert!(amount <= ALLOCATION_TGE, "TGE exceeds cap");
            msg!("Tokenomics: TGE release {} AXQ", amount);
            Ok(())
        }
        TokenomicsInstruction::VestTeam { recipient, amount } => {
            msg!("Tokenomics: Vest {} AXQ to {:?}", amount, recipient);
            Ok(())
        }
        TokenomicsInstruction::SubsidizeVPX { amount } => {
            assert!(amount <= ALLOCATION_VPX_SUBSIDY, "VPX subsidy exceeds cap");
            msg!("Tokenomics: VPX subsidy {} AXQ", amount);
            Ok(())
        }
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_total_supply_allocations_sum() {
        let state = TokenomicsState {
            total_minted: 0,
            vpx_subsidy_distributed: 0,
            rnd_distributed: 0,
            rwa_treasury_locked: 0,
            team_vested: 0,
            strategic_distributed: 0,
            tge_released: 0,
        };
        assert!(state.verify_allocations(), "Allocations must sum to TOTAL_SUPPLY");
    }

    #[test]
    fn test_remaining_supply() {
        let mut state = TokenomicsState {
            total_minted: 25_000_000_000,
            vpx_subsidy_distributed: 0,
            rnd_distributed: 0,
            rwa_treasury_locked: 0,
            team_vested: 0,
            strategic_distributed: 0,
            tge_released: 0,
        };
        assert_eq!(state.remaining_supply(), 475_000_000_000);
        state.total_minted = TOTAL_SUPPLY;
        assert_eq!(state.remaining_supply(), 0);
    }
}
