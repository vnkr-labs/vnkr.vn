/// resolver.rs — ANS Domain resolution (domain → address / ZK-DID)
use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{
    account_info::AccountInfo, msg,
    program_error::ProgramError, pubkey::Pubkey,
};

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub struct ResolveInstruction {
    pub fqdn: String, // e.g. "alice.axq"
}

impl ResolveInstruction {
    pub fn unpack(data: &[u8]) -> Result<Self, ProgramError> {
        Self::try_from_slice(data).map_err(|_| ProgramError::InvalidInstructionData)
    }
}

pub fn process_resolve(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    ix: ResolveInstruction,
) -> solana_program::entrypoint::ProgramResult {
    msg!("ANS: Resolving '{}'", ix.fqdn);
    // TODO: look up DomainRecord PDA, return resolver Pubkey
    Ok(())
}
