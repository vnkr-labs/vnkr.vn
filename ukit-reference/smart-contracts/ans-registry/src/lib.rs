/// ANS Registry — Axio Name Service
/// Domains: .axq | .vpx | .sqx | .kpx | .vrq
/// Each domain = Smart Account (Native Account Abstraction)

pub mod registry;
pub mod resolver;
pub mod security_guard;
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
    let ix = ANSInstruction::unpack(instruction_data)?;
    match ix {
        ANSInstruction::Register(ix) => registry::process_register(program_id, accounts, ix),
        ANSInstruction::Resolve(ix)  => resolver::process_resolve(program_id, accounts, ix),
        ANSInstruction::Transfer(ix) => registry::process_transfer(program_id, accounts, ix),
        ANSInstruction::Revoke(ix)   => registry::process_revoke(program_id, accounts, ix),
    }
}

#[derive(Debug)]
pub enum ANSInstruction {
    Register(registry::RegisterInstruction),
    Resolve(resolver::ResolveInstruction),
    Transfer(registry::TransferInstruction),
    Revoke(registry::RevokeInstruction),
}

impl ANSInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, solana_program::program_error::ProgramError> {
        let (&tag, rest) = data.split_first()
            .ok_or(solana_program::program_error::ProgramError::InvalidInstructionData)?;
        match tag {
            0 => Ok(Self::Register(registry::RegisterInstruction::unpack(rest)?)),
            1 => Ok(Self::Resolve(resolver::ResolveInstruction::unpack(rest)?)),
            2 => Ok(Self::Transfer(registry::TransferInstruction::unpack(rest)?)),
            3 => Ok(Self::Revoke(registry::RevokeInstruction::unpack(rest)?)),
            _ => Err(solana_program::program_error::ProgramError::InvalidInstructionData),
        }
    }
}
