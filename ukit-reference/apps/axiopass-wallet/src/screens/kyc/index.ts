// Zone 2 — KYC & Compliance screens
export { KYCOverviewScreen }  from './KYCOverviewScreen';
export { KYCDocScreen }       from './KYCDocScreen';
export { KYCScanScreen }      from './KYCScanScreen';
export { KYCFaceScreen }      from './KYCFaceScreen';
export { KYCStatusScreen }    from './KYCStatusScreen';

export type { KYCOverviewScreenProps, KYCTier }                  from './KYCOverviewScreen';
export type { KYCDocScreenProps, DocType }                       from './KYCDocScreen';
export type { KYCScanScreenProps, ScanSide, ScanState }          from './KYCScanScreen';
export type { KYCFaceScreenProps, LivenessStep }                 from './KYCFaceScreen';
export type { KYCStatusScreenProps, KYCStatus, KYCRejectionReason } from './KYCStatusScreen';
