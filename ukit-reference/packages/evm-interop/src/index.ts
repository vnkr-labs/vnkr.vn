/**
 * @axioledger/evm-interop
 * EVM Compatibility Layer — Bridge between AxioLedger and EVM chains
 * Used by: KinetoProtocol Bridge, Cross-chain DEX routing
 * License: Apache-2.0
 */

import { ethers } from 'ethers';

// ─── Types ────────────────────────────────────────────────────────────────────

export interface EVMChainConfig {
  chainId: number;
  name: string;
  rpcUrl: string;
  bridgeContractAddress: string;
  nativeCurrency: { symbol: string; decimals: number };
}

export const SUPPORTED_CHAINS: Record<string, EVMChainConfig> = {
  ethereum: {
    chainId: 1,
    name: 'Ethereum Mainnet',
    rpcUrl: 'https://eth.llamarpc.com',
    bridgeContractAddress: '0x0000000000000000000000000000000000000000', // TODO
    nativeCurrency: { symbol: 'ETH', decimals: 18 },
  },
  arbitrum: {
    chainId: 42161,
    name: 'Arbitrum One',
    rpcUrl: 'https://arb1.arbitrum.io/rpc',
    bridgeContractAddress: '0x0000000000000000000000000000000000000000', // TODO
    nativeCurrency: { symbol: 'ETH', decimals: 18 },
  },
  bnb: {
    chainId: 56,
    name: 'BNB Chain',
    rpcUrl: 'https://bsc-dataseed.binance.org',
    bridgeContractAddress: '0x0000000000000000000000000000000000000000', // TODO
    nativeCurrency: { symbol: 'BNB', decimals: 18 },
  },
};

// Minimal ABI for the KPX Bridge contract (Lock/Burn side on EVM)
export const BRIDGE_ABI = [
  'function lock(address token, uint256 amount, bytes32 recipient, uint16 dstChainId) external payable',
  'function release(address token, uint256 amount, address recipient, bytes32 nonce, bytes calldata zkProof) external',
  'event Locked(address indexed token, address indexed sender, uint256 amount, bytes32 recipient, uint16 dstChainId)',
  'event Released(address indexed token, address indexed recipient, uint256 amount, bytes32 nonce)',
] as const;

// ─── EVM Bridge Client ────────────────────────────────────────────────────────

/**
 * EVMBridgeClient — locks tokens on EVM side, triggers KPX Bridge mint on AxioLedger
 *
 * @example
 * const client = new EVMBridgeClient('ethereum', provider);
 * await client.lockTokens('0xA0b8...eB48', ethers.parseEther('100'), recipientANS);
 */
export class EVMBridgeClient {
  private contract: ethers.Contract;
  private chain: EVMChainConfig;

  constructor(chainName: keyof typeof SUPPORTED_CHAINS, provider: ethers.Provider) {
    const chain = SUPPORTED_CHAINS[chainName];
    if (!chain) throw new Error(`Unsupported chain: ${chainName}`);
    this.chain = chain;
    this.contract = new ethers.Contract(chain.bridgeContractAddress, BRIDGE_ABI, provider);
  }

  /**
   * Lock ERC-20 tokens on EVM chain → triggers mint on AxioLedger
   */
  async lockTokens(
    tokenAddress: string,
    amount: bigint,
    axioRecipient: Uint8Array,  // 32-byte AxioLedger public key
    dstChainId: number = 0,     // 0 = AxioLedger
  ): Promise<ethers.TransactionResponse> {
    const recipient = ethers.hexlify(axioRecipient) as `0x${string}`;
    console.log(`[EVMBridge] Locking ${amount} of ${tokenAddress} → AxioLedger`);
    // TODO: approve ERC-20 allowance first
    return this.contract.lock(tokenAddress, amount, recipient, dstChainId) as Promise<ethers.TransactionResponse>;
  }

  /**
   * Parse a Locked event from a transaction receipt
   */
  parseLockedEvent(receipt: ethers.TransactionReceipt) {
    const iface = new ethers.Interface(BRIDGE_ABI);
    const events = receipt.logs
      .map(log => { try { return iface.parseLog(log); } catch { return null; } })
      .filter((e): e is ethers.LogDescription => e !== null && e.name === 'Locked');
    return events;
  }
}

export default EVMBridgeClient;
