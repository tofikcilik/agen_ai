import PageSection from '../components/PageSection';

const rows = [
  ['Sumber Jaya', '186 pelanggan', '94% bayar', '12 keluhan aktif'],
  ['Harapan Baru', '132 pelanggan', '91% bayar', '7 keluhan aktif'],
  ['Tirta Makmur', '98 pelanggan', '96% bayar', '4 keluhan aktif'],
];

export default function StatisticsPage() {
  return (
    <div className="stack">
      <PageSection title="Statistik Wilayah" description="Ringkasan performa layanan untuk root dan kecamatan.">
        <div className="card-grid">
          <article className="stat-card primary"><p>Pelanggan Aktif</p><strong>416</strong></article>
          <article className="stat-card success"><p>Status Bayar</p><strong>93%</strong></article>
          <article className="stat-card warning"><p>Tunggakan</p><strong>29</strong></article>
          <article className="stat-card danger"><p>Keluhan Aktif</p><strong>23</strong></article>
        </div>
      </PageSection>

      <PageSection title="Performa per Desa" description="Statistik dasar untuk monitoring kecamatan.">
        <div className="table-wrap">
          <table className="data-table">
            <thead><tr><th>Desa</th><th>Pelanggan</th><th>Pembayaran</th><th>Keluhan</th></tr></thead>
            <tbody>
              {rows.map((row) => <tr key={row[0]}>{row.map((cell) => <td key={cell}>{cell}</td>)}</tr>)}
            </tbody>
          </table>
        </div>
      </PageSection>
    </div>
  );
}
