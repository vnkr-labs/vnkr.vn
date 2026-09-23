/**
 * @axioledger/zkp-crypto-lib
 * Zero-Knowledge Proof circuits and cryptographic utilities
 * Used by: Veraciphers ($VRQ), Axiopass Wallet, ZK-DID
 * License: MIT
 */

// ─── ZK-DID ───────────────────────────────────────────────────────────────────

export interface ZKIdentity {
  did: string;             // e.g. "did:axq:7Xf8abc..."
  ansDomain?: string;      // e.g. "alice.axq"
  commitment: Uint8Array;  // Pedersen commitment of identity attributes
  nullifier: Uint8Array;   // Prevents double-spending / double-proving
}

export interface ZKProof {
  pi_a: [string, string];
  pi_b: [[string, string], [string, string]];
  pi_c: [string, string];
  protocol: 'groth16' | 'plonk';
  curve: 'bn128' | 'bls12-381';
}

export interface ZKProofInput {
  secret: Uint8Array;      // Private — never transmitted
  publicSignals: string[]; // Public inputs to the circuit
}

// ─── Passkey / WebAuthn integration ───────────────────────────────────────────

export interface PasskeyCredential {
  id: string;
  publicKey: Uint8Array;
  algorithm: 'ES256' | 'RS256';
  transports: ('internal' | 'usb' | 'nfc' | 'ble')[];
}

export interface PasskeyAssertion {
  credentialId: string;
  authenticatorData: Uint8Array;
  clientDataJSON: Uint8Array;
  signature: Uint8Array;
}

// ─── ZK Prover (client-side) ─────────────────────────────────────────────────

/**
 * ZKProver — generates ZK proofs for identity and transaction privacy
 *
 * @example
 * const prover = new ZKProver('groth16', 'bn128');
 * const proof = await prover.prove(input);
 */
export class ZKProver {
  constructor(
    private protocol: ZKProof['protocol'] = 'groth16',
    private curve: ZKProof['curve'] = 'bn128',
  ) {}

  /**
   * Generate a ZK proof from private inputs
   * TODO: integrate with snarkjs / circom WASM circuits
   */
  async prove(input: ZKProofInput): Promise<ZKProof> {
    console.log(`[ZKProver] Generating ${this.protocol}/${this.curve} proof`);
    // TODO: call snarkjs.groth16.fullProve(input, wasmPath, zkeyPath)
    return {
      pi_a: ['0x0', '0x0'],
      pi_b: [['0x0', '0x0'], ['0x0', '0x0']],
      pi_c: ['0x0', '0x0'],
      protocol: this.protocol,
      curve: this.curve,
    };
  }

  /**
   * Verify a ZK proof on-chain (returns true if valid)
   * TODO: call verifier contract
   */
  async verify(proof: ZKProof, publicSignals: string[]): Promise<boolean> {
    console.log(`[ZKProver] Verifying proof, signals=${publicSignals.length}`);
    // TODO: snarkjs.groth16.verify(verificationKey, publicSignals, proof)
    return true;
  }
}

// ─── Passkey Auth ─────────────────────────────────────────────────────────────

/**
 * PasskeyAuth — replaces seed phrases with FaceID/TouchID (WebAuthn)
 *
 * @example
 * const auth = new PasskeyAuth();
 * const cred = await auth.register('alice.axq');
 * const assertion = await auth.authenticate(cred.id);
 */
export class PasskeyAuth {
  /**
   * Register a new Passkey credential (creates a new account)
   * Replaces seed phrase generation
   */
  async register(ansName: string): Promise<PasskeyCredential> {
    console.log(`[PasskeyAuth] Registering passkey for ${ansName}`);
    // TODO: navigator.credentials.create() with WebAuthn
    throw new Error('TODO: WebAuthn registration — requires browser environment');
  }

  /**
   * Authenticate using an existing Passkey (signs a transaction)
   */
  async authenticate(credentialId: string, challenge: Uint8Array): Promise<PasskeyAssertion> {
    console.log(`[PasskeyAuth] Authenticating credential ${credentialId}`);
    // TODO: navigator.credentials.get() with WebAuthn
    throw new Error('TODO: WebAuthn authentication — requires browser environment');
  }
}

// ─── Hash utilities ───────────────────────────────────────────────────────────

/**
 * Compute SHA-256 hash (Node.js / browser compatible)
 */
export async function sha256(data: Uint8Array): Promise<Uint8Array> {
  // TODO: use @noble/hashes sha256 for cross-platform support
  const buf = await globalThis.crypto.subtle.digest('SHA-256', data);
  return new Uint8Array(buf);
}

/**
 * Generate a cryptographically random nullifier
 */
export function generateNullifier(): Uint8Array {
  return globalThis.crypto.getRandomValues(new Uint8Array(32));
}

export default { ZKProver, PasskeyAuth, sha256, generateNullifier };
