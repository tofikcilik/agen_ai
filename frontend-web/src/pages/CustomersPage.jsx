import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { customers } from '../data/mockData';

export default function CustomersPage() {
  return (
    <PageSection
      title="Manajemen Pelanggan"
      description="Kelola data pelanggan, nomor meter, tarif, dan status layanan."
      action={<button className="primary-button">Tambah Pelanggan</button>}
    >
      <DataTable
        columns={[
          { key: 'number', label: 'Nomor Pelanggan' },
          { key: 'name', label: 'Nama' },
          { key: 'village', label: 'Desa' },
          { key: 'meter', label: 'Meter' },
          { key: 'status', label: 'Status' },
        ]}
        rows={customers}
      />
    </PageSection>
  );
}
