import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { complaints } from '../data/mockData';

export default function ComplaintsPage() {
  return (
    <PageSection
      title="Keluhan dan Gangguan"
      description="Pantau laporan pelanggan dan tindak lanjut teknis lapangan."
      action={<button className="primary-button">Buat Tiket</button>}
    >
      <DataTable
        columns={[
          { key: 'customer', label: 'Pelanggan' },
          { key: 'category', label: 'Kategori' },
          { key: 'title', label: 'Judul' },
          { key: 'status', label: 'Status' },
        ]}
        rows={complaints}
      />
    </PageSection>
  );
}
