#!/usr/bin/env node
/**
 * scripts/treasury/auto-sign.js
 * Treasury Engine — Auto-sign approved transaction types
 * Role: treasury-engine (RBAC Tier 3)
 *
 * Approved tx types (only these can be signed):
 *   - treasury.gas_sweep
 *   - treasury.vpx_subsidy.distribute
 *
 * Usage: node scripts/treasury/auto-sign.js
 */

'use strict';

const fs = require('fs');
const crypto = require('crypto');
const https = require('https');

// ── Config ───────────────────────────────────────────────────────────────────
const TREASURY_KEY_PATH = process.env.TREASURY_KEY_PATH || './keys/treasury-engine.json';
const SQX_RPC_URL = process.env.SQX_RPC_URL || 'http://127.0.0.1:8545';
const ALLOWED_TX_TYPES = new Set(['treasury.gas_sweep', 'treasury.vpx_subsidy.distribute']);

// ── Load Key ─────────────────────────────────────────────────────────────────
function loadKey(path) {
  if (!fs.existsSync(path)) {
    console.error(`[Treasury] ERROR: Key not found at ${path}`);
    process.exit(1);
  }
  const stat = fs.statSync(path);
  // Enforce chmod 600
  const mode = (stat.mode & 0o777).toString(8);
  if (mode !== '600') {
    console.error(`[Treasury] SECURITY: Key file permissions are ${mode}, expected 600`);
    console.error('[Treasury] Run: chmod 600 ' + path);
    process.exit(1);
  }
  return JSON.parse(fs.readFileSync(path, 'utf8'));
}

// ── Validate Transaction ──────────────────────────────────────────────────────
function validateTransaction(tx) {
  if (!tx || typeof tx !== 'object') {
    throw new Error('Invalid transaction object');
  }
  if (!ALLOWED_TX_TYPES.has(tx.type)) {
    throw new Error(`Forbidden tx type: ${tx.type}. Allowed: ${[...ALLOWED_TX_TYPES].join(', ')}`);
  }
  if (!tx.amount || tx.amount <= 0) {
    throw new Error('Transaction amount must be positive');
  }
  if (!tx.recipient || typeof tx.recipient !== 'string') {
    throw new Error('Transaction recipient is required');
  }
  return true;
}

// ── Sign Transaction ──────────────────────────────────────────────────────────
function signTransaction(key, tx) {
  const payload = JSON.stringify({
    type: tx.type,
    amount: tx.amount,
    recipient: tx.recipient,
    nonce: crypto.randomBytes(16).toString('hex'),
    timestamp: Date.now(),
  });

  // TODO: use ed25519-dalek via WASM or native addon for production
  // Placeholder: HMAC-SHA256 demonstration
  const sig = crypto
    .createHmac('sha256', Buffer.from(key.secret || 'placeholder', 'hex'))
    .update(payload)
    .digest('hex');

  console.log(`[Treasury] Signed tx type=${tx.type} amount=${tx.amount} sig=${sig.slice(0, 16)}...`);
  return { payload, signature: sig };
}

// ── Main ──────────────────────────────────────────────────────────────────────
async function main() {
  console.log('[Treasury] Auto-sign engine starting...');

  // In production: poll a queue or listen on a local socket
  // For now: sign a test transaction
  const key = loadKey(TREASURY_KEY_PATH);

  const pendingTx = {
    type: 'treasury.gas_sweep',
    amount: 1500,
    recipient: process.env.TREASURY_ADDRESS || 'treasury.axq',
  };

  try {
    validateTransaction(pendingTx);
    const signed = signTransaction(key, pendingTx);
    console.log('[Treasury] ✅ Transaction signed and ready for broadcast');

    // TODO: submit signed tx to SQX RPC
    // await submitToRPC(SQX_RPC_URL, signed);
  } catch (err) {
    console.error('[Treasury] ❌ Signing rejected:', err.message);
    process.exit(1);
  }
}

main().catch(err => {
  console.error('[Treasury] Fatal error:', err);
  process.exit(1);
});
