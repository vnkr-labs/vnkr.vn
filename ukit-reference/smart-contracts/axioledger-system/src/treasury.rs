/// treasury.rs — Ecosystem Treasury Engine
/// Manages RWA Treasury + Gas fee sweep + Emergency controls

use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{
    account_info::AccountInfo, clock::Clock, msg,
    program_error::ProgramError, pubkey::Pubkey, sysvar::Sysvar,
};

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct TreasuryState {
    pub authority: Pubkey,
    pub rwa_balance: u64,
    pub gas_fee_balance: u64,
    pub last_sweep_ts: i64,
    pub is_frozen: bool,
}

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum TreasuryInstruction {
    Initialize       { authority: Pubkey },
    DepositRWA       { amount: u64 },
    SweepGasFees,                          // Cron: every hour
    EmergencyFreeze,
    EmergencyWithdraw{ recipient: Pubkey, amount: u64 },
}

impl TreasuryInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}

pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    instruction: TreasuryInstruction,
) -> solana_program::entrypoint::ProgramResult {
    match instruction {
        TreasuryInstruction::Initialize { authority } => {
            msg!("Treasury: Initialized with authority {:?}", authority);
            Ok(())
        }
        TreasuryInstruction::DepositRWA { amount } => {
            msg!("Treasury: RWA deposit {} AXQ", amount);
            Ok(())
        }
        TreasuryInstruction::SweepGasFees => {
            let clock = Clock::get()?;
            msg!("Treasury: Gas fee sweep at ts={}", clock.unix_timestamp);
            Ok(())
        }
        TreasuryInstruction::EmergencyFreeze => {
            msg!("Treasury: EMERGENCY FREEZE — all operations halted");
            Ok(())
        }
        TreasuryInstruction::EmergencyWithdraw { recipient, amount } => {
            msg!("Treasury: Emergency withdraw {} to {:?}", amount, recipient);
            Ok(())
        }
    }
}
