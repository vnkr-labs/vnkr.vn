/// amm.rs — Constant-Product AMM Router (x * y = k)
/// Supports: AXQ/USDC, VPX/AXQ, SQX/AXQ, KPX/AXQ, VRQ/AXQ pairs

use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{account_info::AccountInfo, msg, program_error::ProgramError, pubkey::Pubkey};

// ─── Fee constants ────────────────────────────────────────────────────────────
pub const SWAP_FEE_BPS: u64 = 30;      // 0.30% swap fee
pub const PROTOCOL_FEE_BPS: u64 = 5;   // 0.05% → protocol treasury
pub const BASIS_POINTS: u64 = 10_000;

// ─── State ───────────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct LiquidityPool {
    pub token_a_mint: Pubkey,
    pub token_b_mint: Pubkey,
    pub reserve_a: u64,
    pub reserve_b: u64,
    pub lp_total_supply: u64,
    pub swap_fee_bps: u64,
}

impl LiquidityPool {
    /// Constant-product quote: given amount_in of token A, returns amount_out of token B
    /// Formula: amount_out = (reserve_b * amount_in_after_fee) / (reserve_a + amount_in_after_fee)
    pub fn get_amount_out(&self, amount_in: u64, a_to_b: bool) -> Result<u64, ProgramError> {
        let (reserve_in, reserve_out) = if a_to_b {
            (self.reserve_a, self.reserve_b)
        } else {
            (self.reserve_b, self.reserve_a)
        };

        if reserve_in == 0 || reserve_out == 0 {
            return Err(ProgramError::InvalidAccountData);
        }

        let fee_amount = amount_in
            .checked_mul(self.swap_fee_bps)
            .and_then(|v| v.checked_div(BASIS_POINTS))
            .ok_or(ProgramError::ArithmeticOverflow)?;

        let amount_in_after_fee = amount_in
            .checked_sub(fee_amount)
            .ok_or(ProgramError::ArithmeticOverflow)?;

        let numerator = reserve_out
            .checked_mul(amount_in_after_fee)
            .ok_or(ProgramError::ArithmeticOverflow)?;

        let denominator = reserve_in
            .checked_add(amount_in_after_fee)
            .ok_or(ProgramError::ArithmeticOverflow)?;

        Ok(numerator / denominator)
    }
}

// ─── Instructions ────────────────────────────────────────────────────────────
#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum AMMInstruction {
    InitializePool { initial_a: u64, initial_b: u64 },
    AddLiquidity    { amount_a: u64, amount_b: u64, min_lp: u64 },
    RemoveLiquidity { lp_amount: u64, min_a: u64, min_b: u64 },
    Swap            { amount_in: u64, min_out: u64, a_to_b: bool },
}

pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    data: &[u8],
) -> solana_program::entrypoint::ProgramResult {
    let ix = AMMInstruction::try_from_slice(data)
        .map_err(|_| ProgramError::InvalidInstructionData)?;
    match ix {
        AMMInstruction::InitializePool { initial_a, initial_b } => {
            msg!("KPX AMM: Init pool reserve_a={} reserve_b={}", initial_a, initial_b);
            Ok(())
        }
        AMMInstruction::AddLiquidity { amount_a, amount_b, min_lp } => {
            msg!("KPX AMM: Add liquidity a={} b={} min_lp={}", amount_a, amount_b, min_lp);
            Ok(())
        }
        AMMInstruction::RemoveLiquidity { lp_amount, min_a, min_b } => {
            msg!("KPX AMM: Remove liquidity lp={} min_a={} min_b={}", lp_amount, min_a, min_b);
            Ok(())
        }
        AMMInstruction::Swap { amount_in, min_out, a_to_b } => {
            msg!("KPX AMM: Swap amount_in={} min_out={} a_to_b={}", amount_in, min_out, a_to_b);
            Ok(())
        }
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    fn mock_pool() -> LiquidityPool {
        LiquidityPool {
            token_a_mint: Pubkey::default(),
            token_b_mint: Pubkey::default(),
            reserve_a: 1_000_000,
            reserve_b: 1_000_000,
            lp_total_supply: 1_000_000,
            swap_fee_bps: SWAP_FEE_BPS,
        }
    }

    #[test]
    fn test_constant_product_swap() {
        let pool = mock_pool();
        let out = pool.get_amount_out(1000, true).unwrap();
        // With 0.30% fee on 1:1 pool, ~997 out for 1000 in
        assert!(out > 990 && out < 1000, "Expected ~997, got {}", out);
    }

    #[test]
    fn test_swap_fee_reduces_output() {
        let mut pool = mock_pool();
        let out_with_fee = pool.get_amount_out(1000, true).unwrap();
        pool.swap_fee_bps = 0; // no fee
        let out_no_fee = pool.get_amount_out(1000, true).unwrap();
        assert!(out_with_fee < out_no_fee);
    }
}
