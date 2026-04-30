import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { bills } from '../data/mockData';

export default function BillsPage() {
  return (
    <PageSection
      title="Tagihan"
      description="Review hasil generate tagihan dari catatan meter bulanan."
      action={<button className="primary-button">Generate Tagihan</button>}
    >
      <DataTable
        columns={[
          { key: 'customer', label: 'Pelanggan' },
          { key: 'month', label: 'Bulan' },
          { key: 'usage', label: 'Pemakaian' },
          { key: 'amount', label: 'Nilai Tagihan' },
          { key: 'status', label: 'Status' },
        ]}
        rows={bills}
      />
    </PageSection>
  );
}
