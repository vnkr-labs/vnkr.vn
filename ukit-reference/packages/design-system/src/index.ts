/**
 * @axioledger/axio-design-system — v2.0.0
 * Import CSS in your app entry:
 *   import '@axioledger/axio-design-system/styles';
 */

// ── Providers ──────────────────────────────────────────
export { AxioProvider } from './providers/AxioProvider';
export { ThemeContext } from './providers/ThemeContext';
export type { AxioProviderProps } from './providers/AxioProvider';
export type { Theme, ResolvedTheme } from './providers/ThemeContext';
export type { ANSResolverConfig } from './providers/ANSContext';

// ── Hooks ───────────────────────────────────────────────
export { useTheme } from './hooks/useTheme';
export { useANSResolver } from './hooks/useANSResolver';
export { useToast } from './hooks/useToast';
export type { ToastVariant, ToastOptions, ToastItem } from './hooks/useToast';

// ── Types ───────────────────────────────────────────────
export type { TLPLevel, TLPConfig } from './types/tlp';
export { TLP_CONFIG, resolveTLPLevel, getTLPConfig } from './types/tlp';

// ── Core Components ─────────────────────────────────────
export { Button } from './components/Button';
export { Input } from './components/Input';
export { Card, CardHeader, CardBody, CardFooter } from './components/Card';
export { Badge, Chip } from './components/Badge';
export { Toggle } from './components/Toggle';
export { Avatar, AvatarGroup } from './components/Avatar';
export { Tooltip } from './components/Tooltip';
export { Skeleton, SkeletonCard } from './components/Skeleton';
export { Navbar } from './components/Navbar';
export { Modal, BottomSheet } from './components/Modal';
export { Toast, ToastContainer } from './components/Toast';
export type { ButtonProps, ButtonType, ButtonColor, ButtonSize } from './components/Button';
export type { InputProps, InputSize, InputType } from './components/Input';
export type { CardProps } from './components/Card';
export type { BadgeProps, BadgeVariant, ChipProps } from './components/Badge';
export type { ToggleProps } from './components/Toggle';
export type { AvatarProps, AvatarGroupProps, AvatarSize } from './components/Avatar';
export type { TooltipProps, TooltipPlacement } from './components/Tooltip';
export type { SkeletonProps } from './components/Skeleton';
export type { NavbarProps, NavItem } from './components/Navbar';
export type { ModalProps, ModalVariant, ModalAction, BottomSheetProps, BottomSheetVariant } from './components/Modal';
export type { ToastItemProps, ToastContainerProps } from './components/Toast';

// ── Form Components ─────────────────────────────────────
export { OTPInput } from './components/OTPInput';
export { Checkbox, RadioButton } from './components/Checkbox';
export { SearchBar } from './components/SearchBar';
export { Dropdown } from './components/Dropdown';
export type { OTPInputProps } from './components/OTPInput';
export type { CheckboxProps, CheckboxState, RadioButtonProps } from './components/Checkbox';
export type { SearchBarProps } from './components/SearchBar';
export type { DropdownProps, DropdownOption } from './components/Dropdown';

// ── Feedback Components ─────────────────────────────────
export { Alert, SecurityAlert } from './components/Alert';
export { ProgressBar, StepIndicator } from './components/ProgressBar';
export { EmptyState } from './components/EmptyState';
export type { AlertProps, AlertVariant, SecurityAlertProps } from './components/Alert';
export type { ProgressBarProps, ProgressVariant, StepIndicatorProps } from './components/ProgressBar';
export type { EmptyStateProps, EmptyStateVariant } from './components/EmptyState';

// ── Crypto Components ───────────────────────────────────
export {
  NamespaceBadge,
  AddressDisplay,
  PasskeyButton,
  CryptoAssetCard,
  TransactionItem,
  BalanceDisplay,
  PriceTicker,
} from './components/crypto';
export type {
  NamespaceBadgeProps,
  AddressDisplayProps,
  PasskeyButtonProps, PasskeyAction,
  CryptoAssetCardProps, TransactionItemProps, TxType, TxStatus,
  BalanceDisplayProps, PriceTickerProps,
} from './components/crypto';

// ── Onboarding / Wallet Components ──────────────────────
export { PINPad } from './components/PINPad';
export type { PINPadProps, PINPadMode } from './components/PINPad';

export { CardVisual } from './components/CardVisual';
export type { CardVisualProps, CardVariant, CardScheme, CardSkin } from './components/CardVisual';

export { QRDisplay } from './components/QRDisplay';
export type { QRDisplayProps } from './components/QRDisplay';

export { LivenessFrame } from './components/LivenessFrame';
export type { LivenessFrameProps, LivenessStep } from './components/LivenessFrame';

export { GasFeeSelector } from './components/GasFeeSelector';
export type { GasFeeSelectorProps, GasSpeed, GasOption } from './components/GasFeeSelector';

// ── Icon System ──────────────────────────────────────────
export { Icon } from './components/Icon';
export type { IconProps, IconSize, IconName } from './components/Icon';
