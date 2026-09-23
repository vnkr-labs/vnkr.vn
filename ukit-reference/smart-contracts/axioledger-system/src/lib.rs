/// axioledger-system/src/lib.rs
/// AXQ Core Hub — DAO Governance, Tokenomics, Ecosystem Treasury
/// Target: ~50,000 LOC | License: BSL 1.1

pub mod dao;
pub mod tokenomics;
pub mod treasury;
pub mod errors;

use solana_program::{
    account_info::AccountInfo,
    entrypoint,
    entrypoint::ProgramResult,
    pubkey::Pubkey,
};

entrypoint!(process_instruction);

pub fn process_instruction(
    program_id: &Pubkey,
    accounts: &[AccountInfo],
    instruction_data: &[u8],
) -> ProgramResult {
    let instruction = AXQInstruction::unpack(instruction_data)?;
    match instruction {
        AXQInstruction::Governance(ix) => dao::process(program_id, accounts, ix),
        AXQInstruction::Treasury(ix)   => treasury::process(program_id, accounts, ix),
        AXQInstruction::Tokenomics(ix) => tokenomics::process(program_id, accounts, ix),
    }
}

#[derive(Debug)]
pub enum AXQInstruction {
    Governance(dao::GovernanceInstruction),
    Treasury(treasury::TreasuryInstruction),
    Tokenomics(tokenomics::TokenomicsInstruction),
}

impl AXQInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, solana_program::program_error::ProgramError> {
        let (&tag, rest) = data.split_first()
            .ok_or(solana_program::program_error::ProgramError::InvalidInstructionData)?;
        match tag {
            0 => Ok(Self::Governance(dao::GovernanceInstruction::unpack(rest)?)),
            1 => Ok(Self::Treasury(treasury::TreasuryInstruction::unpack(rest)?)),
            2 => Ok(Self::Tokenomics(tokenomics::TokenomicsInstruction::unpack(rest)?)),
            _ => Err(solana_program::program_error::ProgramError::InvalidInstructionData),
        }
    }
}
