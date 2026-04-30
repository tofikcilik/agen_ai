import PageSection from '../components/PageSection';
import { dashboardSummary } from '../data/mockData';
import { useAuth } from '../contexts/AuthContext';

export default function DashboardPage() {
  const { user } = useAuth();

  return (
    <div className="stack">
      <PageSection
        title="Dashboard Monitoring"
        description={`Ringkasan operasional untuk role ${user?.role}.`}
      >
        <div className="card-grid">
          {dashboardSummary.map((item) => (
            <article key={item.label} className={`stat-card ${item.tone}`}>
              <p>{item.label}</p>
              <strong>{item.value}</strong>
            </article>
          ))}
        </div>
      </PageSection>
      <PageSection
        title="Fokus Operasional Hari Ini"
        description="Panel ini dirancang untuk cepat discan oleh operator kecamatan, desa, maupun petugas."
      >
        <div className="highlight-grid">
          <div className="highlight-panel">
            <h3>Tagihan Belum Lunas</h3>
            <p>142 pelanggan masih memiliki tunggakan aktif yang perlu ditindaklanjuti.</p>
          </div>
          <div className="highlight-panel">
            <h3>Pembacaan Meter</h3>
            <p>78% pelanggan bulan ini sudah tercatat, sisa 22% menunggu kunjungan lapangan.</p>
          </div>
          <div className="highlight-panel">
            <h3>Keluhan Aktif</h3>
            <p>Keluhan terbanyak berasal dari gangguan distribusi di dua desa prioritas.</p>
          </div>
        </div>
      </PageSection>
    </div>
  );
}
