/**
 * AppShell — Navigation + Router
 * Bottom tab bar (Home | Crypto | Card | Transfer | Profile)
 * Stack-style screen routing (no external router dependency)
 */
'use client';
import React, { useState } from 'react';

// ── Icon helper ────────────────────────────────────────
function TabIcon({ name, active }: { name: string; active: boolean }) {
  const color = active ? '#49dbc8' : '#8f9bb3';
  const icons: Record<string, React.ReactNode> = {
    home: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H5a1 1 0 01-1-1V9.5z" stroke={color} strokeWidth="1.6" strokeLinejoin="round" />
        <path d="M9 21V12h6v9" stroke={color} strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
      </svg>
    ),
    crypto: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle cx="12" cy="12" r="9" stroke={color} strokeWidth="1.6" />
        <path d="M9 8h4.5a2 2 0 010 4H9m0 0h5a2 2 0 010 4H9M9 8V6m0 10v2m3-12v2m0 10v2" stroke={color} strokeWidth="1.5" strokeLinecap="round" />
      </svg>
    ),
    card: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <rect x="2" y="5" width="20" height="14" rx="2" stroke={color} strokeWidth="1.6" />
        <path d="M2 10h20" stroke={color} strokeWidth="1.6" strokeLinecap="round" />
        <rect x="5" y="14" width="4" height="2" rx="0.5" fill={color} />
      </svg>
    ),
    transfer: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M5 12h14M14 7l5 5-5 5" stroke={color} strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
        <path d="M10 7l-5 5 5 5" stroke={color} strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
      </svg>
    ),
    profile: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <circle cx="12" cy="8" r="4" stroke={color} strokeWidth="1.6" />
        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke={color} strokeWidth="1.6" strokeLinecap="round" />
      </svg>
    ),
  };
  return <>{icons[name] ?? null}</>;
}

// ── Screen imports ───────────────────────────────────────
import { SplashScreen, OnboardingScreen, RegisterScreen, OTPScreen, PINScreen, PasskeyScreen } from './onboarding';
import { KYCOverviewScreen, KYCDocScreen, KYCScanScreen, KYCFaceScreen, KYCStatusScreen } from './kyc';
import { HomeScreen, CashbackScreen, AnalyticsScreen } from './home';
import { CardCenterScreen, IssueCardScreen, AddBalanceScreen, CardSettingsScreen } from './card';
import { CryptoPortfolioScreen, SwapScreen, ReceiveScreen, SendScreen, AddressBookScreen } from './crypto';
import { TransferHubScreen, AmountScreen, HomeServicesScreen, TxReviewScreen, TxAuthScreen, ReceiptScreen } from './transfer';
import { ProfileScreen, EditProfileScreen, SecurityScreen, SettingsScreen, FAQScreen } from './profile';
import { NotificationsScreen } from './profile/NotificationsScreen';
import { WalletDetailsScreen } from './crypto/WalletDetailsScreen';
import { NetworkErrorScreen, ServerErrorScreen, SessionExpiredScreen, EmptyHistoryScreen, EmptyPortfolioScreen, EmptyCardsScreen, JailbreakWarningScreen, AccountBlockedScreen } from './system';

export type ScreenName =
  // Onboarding
  | 'splash' | 'onboarding' | 'register' | 'otp' | 'pin_create' | 'pin_confirm' | 'passkey'
  // KYC
  | 'kyc_overview' | 'kyc_doc' | 'kyc_scan_front' | 'kyc_scan_back' | 'kyc_face' | 'kyc_status'
  // Tabs
  | 'home' | 'cashback' | 'analytics'
  | 'crypto' | 'swap' | 'receive' | 'send' | 'address_book' | 'wallet_details'
  | 'card_center' | 'issue_card' | 'add_balance' | 'card_settings'
  | 'transfer_hub' | 'amount' | 'home_services' | 'tx_review' | 'tx_auth' | 'receipt'
  | 'profile' | 'edit_profile' | 'security' | 'settings' | 'faq' | 'notifications'
  // System
  | 'network_error' | 'server_error' | 'session_expired'
  | 'empty_history' | 'empty_portfolio' | 'empty_cards'
  | 'jailbreak' | 'account_blocked';

type Tab = 'home' | 'crypto' | 'card' | 'transfer' | 'profile';

const TAB_SCREENS: Record<Tab, ScreenName> = {
  home:     'home',
  crypto:   'crypto',
  card:     'card_center',
  transfer: 'transfer_hub',
  profile:  'profile',
};

const TAB_CONFIG: { id: Tab; label: string }[] = [
  { id: 'home',     label: 'Home'    },
  { id: 'crypto',   label: 'Crypto'  },
  { id: 'card',     label: 'Card'    },
  { id: 'transfer', label: 'Pay'     },
  { id: 'profile',  label: 'Profile' },
];

// Screens that show the bottom nav
const TAB_SCREENS_SET = new Set<ScreenName>([
  'home', 'cashback', 'analytics',
  'crypto', 'swap', 'receive', 'send', 'address_book',
  'card_center', 'issue_card', 'add_balance', 'card_settings',
  'transfer_hub', 'amount', 'home_services',
  'profile', 'edit_profile', 'security', 'settings', 'faq', 'notifications',
  'wallet_details',
]);

export function AppShell() {
  const [screen, setScreen] = useState<ScreenName>('splash');
  const [activeTab, setActiveTab] = useState<Tab>('home');
  const [createdPIN, setCreatedPIN] = useState('');

  const go = (s: ScreenName) => setScreen(s);
  const showTabs = TAB_SCREENS_SET.has(screen);

  const handleTabChange = (tab: Tab) => {
    setActiveTab(tab);
    go(TAB_SCREENS[tab]);
  };

  return (
    <div style={{ position: 'relative', maxWidth: 390, margin: '0 auto', minHeight: '100svh', overflow: 'hidden' }}>
      {/* ── Screen Router ── */}
      {screen === 'splash'         && <SplashScreen onDone={() => go('onboarding')} />}
      {screen === 'onboarding'     && <OnboardingScreen onComplete={() => go('register')} />}
      {screen === 'register'       && <RegisterScreen onSubmit={() => go('otp')} />}
      {screen === 'otp'            && <OTPScreen maskedIdentifier="+84 *** *** 789" onComplete={() => go('pin_create')} onBack={() => go('register')} />}
      {screen === 'pin_create'     && <PINScreen mode="create" onComplete={(p) => { setCreatedPIN(p); go('pin_confirm'); }} />}
      {screen === 'pin_confirm'    && <PINScreen mode="confirm" targetPIN={createdPIN} onComplete={() => go('passkey')} />}
      {screen === 'passkey'        && <PasskeyScreen onSuccess={() => go('kyc_overview')} onSkip={() => go('kyc_overview')} onFallbackToPIN={() => go('pin_create')} />}

      {screen === 'kyc_overview'   && <KYCOverviewScreen onStart={() => go('kyc_doc')} />}
      {screen === 'kyc_doc'        && <KYCDocScreen onSelect={() => go('kyc_scan_front')} onBack={() => go('kyc_overview')} />}
      {screen === 'kyc_scan_front' && <KYCScanScreen side="front" onCapture={() => go('kyc_scan_back')} onBack={() => go('kyc_doc')} />}
      {screen === 'kyc_scan_back'  && <KYCScanScreen side="back" onCapture={() => go('kyc_face')} onBack={() => go('kyc_scan_front')} />}
      {screen === 'kyc_face'       && <KYCFaceScreen onComplete={() => go('kyc_status')} onBack={() => go('kyc_scan_back')} />}
      {screen === 'kyc_status'     && <KYCStatusScreen status="approved" onGoHome={() => go('home')} />}

      {screen === 'home'           && <HomeScreen onSend={() => go('transfer_hub')} onReceive={() => go('receive')} onTopUp={() => go('add_balance')} onSwap={() => go('swap')} onCard={() => go('card_center')} onCashback={() => go('cashback')} />}
      {screen === 'cashback'       && <CashbackScreen />}
      {screen === 'analytics'      && <AnalyticsScreen />}

      {screen === 'card_center'    && <CardCenterScreen onIssue={() => go('issue_card')} />}
      {screen === 'issue_card'     && <IssueCardScreen onBack={() => go('card_center')} onConfirm={() => go('card_center')} />}
      {screen === 'add_balance'    && <AddBalanceScreen onBack={() => go('home')} onConfirm={() => go('tx_review')} />}
      {screen === 'card_settings'  && <CardSettingsScreen onBack={() => go('card_center')} />}

      {screen === 'crypto'         && <CryptoPortfolioScreen onSelectAsset={() => go('wallet_details')} />}
      {screen === 'swap'           && <SwapScreen onBack={() => go('crypto')} />}
      {screen === 'receive'        && <ReceiveScreen onBack={() => go('crypto')} />}
      {screen === 'send'           && <SendScreen onBack={() => go('crypto')} onReview={() => go('tx_review')} />}
      {screen === 'address_book'   && <AddressBookScreen onBack={() => go('send')} onSelect={() => go('send')} />}
      {screen === 'wallet_details' && <WalletDetailsScreen onBack={() => go('crypto')} onSend={() => go('send')} onReceive={() => go('receive')} onSwap={() => go('swap')} />}

      {screen === 'transfer_hub'   && <TransferHubScreen onSelect={() => go('amount')} />}
      {screen === 'amount'         && <AmountScreen onBack={() => go('transfer_hub')} onNext={() => go('tx_review')} />}
      {screen === 'home_services'  && <HomeServicesScreen onBack={() => go('transfer_hub')} />}
      {screen === 'tx_review'      && <TxReviewScreen onBack={() => go('amount')} onConfirm={() => go('tx_auth')} />}
      {screen === 'tx_auth'        && <TxAuthScreen onBack={() => go('tx_review')} onComplete={() => go('receipt')} />}
      {screen === 'receipt'        && <ReceiptScreen onHome={() => go('home')} onNewTransfer={() => go('transfer_hub')} />}

      {screen === 'profile'        && <ProfileScreen onEdit={() => go('edit_profile')} onSettings={() => go('settings')} onSecurity={() => go('security')} onFAQ={() => go('faq')} onLogout={() => go('splash')} onNotifications={() => go('notifications')} />}
      {screen === 'notifications'  && <NotificationsScreen onBack={() => go('profile')} />}
      {screen === 'edit_profile'   && <EditProfileScreen onBack={() => go('profile')} onSave={() => go('profile')} />}
      {screen === 'security'       && <SecurityScreen onBack={() => go('profile')} />}
      {screen === 'settings'       && <SettingsScreen onBack={() => go('profile')} />}
      {screen === 'faq'            && <FAQScreen onBack={() => go('profile')} />}

      {screen === 'network_error'  && <NetworkErrorScreen onRetry={() => go('home')} />}
      {screen === 'server_error'   && <ServerErrorScreen onRetry={() => go('home')} />}
      {screen === 'session_expired'&& <SessionExpiredScreen onLogin={() => go('register')} />}
      {screen === 'empty_history'  && <EmptyHistoryScreen onTransfer={() => go('transfer_hub')} />}
      {screen === 'empty_portfolio'&& <EmptyPortfolioScreen onBuy={() => go('swap')} />}
      {screen === 'empty_cards'    && <EmptyCardsScreen onIssue={() => go('issue_card')} />}
      {screen === 'jailbreak'      && <JailbreakWarningScreen />}
      {screen === 'account_blocked'&& <AccountBlockedScreen />}

      {/* ── Bottom Tab Bar ── */}
      {showTabs && (
        <nav style={navStyle} role="navigation" aria-label="Main navigation">
          {TAB_CONFIG.map((tab) => {
            const isActive = activeTab === tab.id;
            return (
              <button
                key={tab.id}
                style={{ ...tabBtnStyle, color: isActive ? '#49dbc8' : '#8f9bb3' }}
                onClick={() => handleTabChange(tab.id)}
                aria-label={tab.label}
                aria-current={isActive ? 'page' : undefined}
              >
                <TabIcon name={tab.id} active={isActive} />
                <span style={{ fontSize: 10, fontWeight: isActive ? 700 : 500, marginTop: 3, display: 'block' }}>{tab.label}</span>
                {isActive && <span style={activeDotStyle} aria-hidden="true" />}
              </button>
            );
          })}
        </nav>
      )}
    </div>
  );
}

const navStyle: React.CSSProperties = {
  position: 'fixed',
  bottom: 0,
  left: '50%',
  transform: 'translateX(-50%)',
  width: '100%',
  maxWidth: 390,
  background: 'rgba(255,255,255,0.95)',
  backdropFilter: 'blur(12px)',
  borderTop: '1px solid #e4e9f2',
  display: 'flex',
  paddingBottom: 'env(safe-area-inset-bottom, 0px)',
  zIndex: 100,
};

const tabBtnStyle: React.CSSProperties = {
  flex: 1,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  justifyContent: 'center',
  padding: '10px 0',
  background: 'none',
  border: 'none',
  cursor: 'pointer',
  position: 'relative',
  transition: 'color 0.15s ease',
};

const activeDotStyle: React.CSSProperties = {
  position: 'absolute',
  top: 6,
  width: 4,
  height: 4,
  borderRadius: '50%',
  background: '#49dbc8',
};
