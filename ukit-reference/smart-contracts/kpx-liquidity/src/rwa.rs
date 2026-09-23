/// rwa.rs — Real World Asset Treasury
use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{account_info::AccountInfo, msg, program_error::ProgramError, pubkey::Pubkey};

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct RWAAsset {
    pub asset_id: [u8; 32],
    pub legal_document_hash: [u8; 32], // SHA-256 of off-chain legal docs
    pub issuer: Pubkey,
    pub face_value_usd_cents: u64,
    pub token_mint: Pubkey,
    pub is_verified: bool,
}

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum RWAInstruction {
    RegisterAsset { asset: RWAAsset },
    VerifyAsset   { asset_id: [u8; 32] },
    DepositToTreasury { asset_id: [u8; 32], amount: u64 },
    WithdrawFromTreasury { asset_id: [u8; 32], amount: u64, recipient: Pubkey },
}

pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    data: &[u8],
) -> solana_program::entrypoint::ProgramResult {
    let ix = RWAInstruction::try_from_slice(data)
        .map_err(|_| ProgramError::InvalidInstructionData)?;
    match ix {
        RWAInstruction::RegisterAsset { asset } => {
            msg!("KPX RWA: Register asset id={:?} value={}", asset.asset_id, asset.face_value_usd_cents);
            Ok(())
        }
        RWAInstruction::VerifyAsset { asset_id } => {
            msg!("KPX RWA: Verify asset id={:?}", asset_id);
            Ok(())
        }
        RWAInstruction::DepositToTreasury { asset_id, amount } => {
            msg!("KPX RWA: Deposit id={:?} amount={}", asset_id, amount);
            Ok(())
        }
        RWAInstruction::WithdrawFromTreasury { asset_id, amount, recipient } => {
            msg!("KPX RWA: Withdraw id={:?} amount={} to={:?}", asset_id, amount, recipient);
            Ok(())
        }
    }
}
