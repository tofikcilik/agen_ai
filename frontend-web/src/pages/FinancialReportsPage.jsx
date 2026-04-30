import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { reports } from '../data/mockData';

export default function FinancialReportsPage() {
  return (
    <PageSection
      title="Laporan Keuangan"
      description="Ringkasan pendapatan, transaksi, dan tunggakan per desa untuk monitoring kecamatan."
      action={<button className="secondary-button">Unduh Rekap</button>}
    >
      <DataTable
        columns={[
          { key: 'village', label: 'Desa' },
          { key: 'revenue', label: 'Penerimaan' },
          { key: 'arrears', label: 'Tunggakan' },
          { key: 'transactions', label: 'Transaksi' },
        ]}
        rows={reports}
      />
    </PageSection>
  );
}
