/// kpx-liquidity/src/lib.rs
/// KinetoProtocol — AMM Router + Cross-chain Bridge + RWA Treasury
/// Target: ~80,000 LOC | License: BSL 1.1

pub mod amm;
pub mod bridge;
pub mod rwa;
pub mod errors;

use solana_program::{
    account_info::AccountInfo, entrypoint, entrypoint::ProgramResult, pubkey::Pubkey,
};

entrypoint!(process_instruction);

pub fn process_instruction(
    program_id: &Pubkey,
    accounts: &[AccountInfo],
    instruction_data: &[u8],
) -> ProgramResult {
    let (&tag, rest) = instruction_data.split_first()
        .ok_or(solana_program::program_error::ProgramError::InvalidInstructionData)?;
    match tag {
        0 => amm::process(program_id, accounts, rest),
        1 => bridge::process(program_id, accounts, rest),
        2 => rwa::process(program_id, accounts, rest),
        _ => Err(solana_program::program_error::ProgramError::InvalidInstructionData),
    }
}
