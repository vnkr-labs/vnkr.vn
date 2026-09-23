/// bridge.rs — Cross-chain Bridge (Lock/Mint + Burn/Release)
use borsh::{BorshDeserialize, BorshSerialize};
use solana_program::{account_info::AccountInfo, msg, program_error::ProgramError, pubkey::Pubkey};

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone, PartialEq)]
pub enum ChainId {
    AxioLedger = 0,
    Ethereum   = 1,
    Solana     = 2,
    BNBChain   = 3,
    Arbitrum   = 4,
}

#[derive(BorshSerialize, BorshDeserialize, Debug, Clone)]
pub struct BridgeMessage {
    pub nonce: u64,
    pub src_chain: ChainId,
    pub dst_chain: ChainId,
    pub sender: [u8; 32],
    pub recipient: [u8; 32],
    pub token_mint: Pubkey,
    pub amount: u64,
    pub zk_proof_hash: [u8; 32], // ZK proof of valid lock on source chain
}

#[derive(BorshSerialize, BorshDeserialize, Debug)]
pub enum BridgeInstruction {
    LockAndBurn  { message: BridgeMessage },
    MintAndRelease{ message: BridgeMessage },
    VerifyProof  { nonce: u64, proof: Vec<u8> },
}

pub fn process(
    _program_id: &Pubkey,
    _accounts: &[AccountInfo],
    data: &[u8],
) -> solana_program::entrypoint::ProgramResult {
    let ix = BridgeInstruction::try_from_slice(data)
        .map_err(|_| ProgramError::InvalidInstructionData)?;
    match ix {
        BridgeInstruction::LockAndBurn { message } => {
            msg!("KPX Bridge: LockAndBurn nonce={} amount={}", message.nonce, message.amount);
            Ok(())
        }
        BridgeInstruction::MintAndRelease { message } => {
            msg!("KPX Bridge: MintAndRelease nonce={} amount={}", message.nonce, message.amount);
            Ok(())
        }
        BridgeInstruction::VerifyProof { nonce, .. } => {
            msg!("KPX Bridge: VerifyProof nonce={}", nonce);
            Ok(())
        }
    }
}
