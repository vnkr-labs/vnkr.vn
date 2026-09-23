/**
 * apps/axq-governance-ui/src/app/page.tsx
 * AXQ DAO Governance Dashboard
 * Giai đoạn 3: Quản trị Tam Quyền Phân Lập
 */

import { ProposalList } from '../components/ProposalList';
import { VotingPanel } from '../components/VotingPanel';
import { GuardianCouncil } from '../components/GuardianCouncil';
import { TreasuryStats } from '../components/TreasuryStats';

export default function GovernancePage() {
  return (
    <main style={{ maxWidth: 1200, margin: '0 auto', padding: '2rem' }}>
      <header>
        <h1>AXQ Governance</h1>
        <p>Tam Quyền Phân Lập — Lập pháp · Tư pháp · Hành pháp</p>
      </header>

      <section aria-label="Treasury Statistics">
        <TreasuryStats />
      </section>

      <section aria-label="Active Proposals">
        <h2>Proposals</h2>
        <ProposalList />
      </section>

      <section aria-label="Cast Vote">
        <h2>Cast Vote</h2>
        <p>Quadratic Voting: votes = √(token balance)</p>
        <VotingPanel />
      </section>

      <section aria-label="Guardian Council">
        <h2>Guardian Council</h2>
        <p>5 seats · Veto requires 4/5 within Objection Window (3 days)</p>
        <GuardianCouncil />
      </section>
    </main>
  );
}
