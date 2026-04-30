import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { payments } from '../data/mockData';

export default function PaymentsPage() {
  return (
    <PageSection
      title="Pembayaran"
      description="Pencatatan pembayaran lapangan dan verifikasi penerimaan."
      action={<button className="primary-button">Catat Pembayaran</button>}
    >
      <DataTable
        columns={[
          { key: 'customer', label: 'Pelanggan' },
          { key: 'date', label: 'Tanggal' },
          { key: 'amount', label: 'Jumlah' },
          { key: 'method', label: 'Metode' },
          { key: 'officer', label: 'Petugas' },
        ]}
        rows={payments}
      />
    </PageSection>
  );
}
