/// security_guard.rs — Namespace Security Guard
/// Blocks malicious / squatting / reserved namespaces

use solana_program::msg;

// Reserved namespaces that cannot be registered
const RESERVED_LABELS: &[&str] = &[
    "axioledger", "admin", "root", "system", "dao",
    "treasury", "guardian", "council", "foundation",
    "sol", "eth", "btc", "usdc", "usdt",
    "null", "undefined", "test", "localhost",
];

// Patterns indicating homograph attacks (simplified — extend with full Unicode)
const FORBIDDEN_CHARS: &[char] = &[
    '\u{0430}', // Cyrillic а (looks like Latin a)
    '\u{043E}', // Cyrillic о
    '\u{0435}', // Cyrillic е
    '\u{0440}', // Cyrillic р
    '\u{0441}', // Cyrillic с
    '\u{0445}', // Cyrillic х
];

pub struct SecurityGuard;

impl SecurityGuard {
    /// Returns true if the label is safe to register
    pub fn is_safe(label: &str) -> bool {
        let lower = label.to_lowercase();

        // Block reserved labels
        if RESERVED_LABELS.contains(&lower.as_str()) {
            msg!("SecurityGuard: '{}' is a reserved namespace", label);
            return false;
        }

        // Block homograph / lookalike characters
        for ch in label.chars() {
            if FORBIDDEN_CHARS.contains(&ch) {
                msg!("SecurityGuard: '{}' contains forbidden Unicode char U+{:04X}", label, ch as u32);
                return false;
            }
        }

        // Block labels that are purely numeric (reserved for numeric IDs)
        if lower.chars().all(|c| c.is_ascii_digit()) {
            msg!("SecurityGuard: '{}' is a purely numeric label", label);
            return false;
        }

        true
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_reserved_labels_blocked() {
        assert!(!SecurityGuard::is_safe("admin"));
        assert!(!SecurityGuard::is_safe("ADMIN"));
        assert!(!SecurityGuard::is_safe("treasury"));
    }

    #[test]
    fn test_valid_labels_pass() {
        assert!(SecurityGuard::is_safe("alice"));
        assert!(SecurityGuard::is_safe("mybusiness"));
        assert!(SecurityGuard::is_safe("axio-user-01"));
    }

    #[test]
    fn test_pure_numeric_blocked() {
        assert!(!SecurityGuard::is_safe("12345"));
    }
}
