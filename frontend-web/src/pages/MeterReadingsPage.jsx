import DataTable from '../components/DataTable';
import PageSection from '../components/PageSection';
import { meterReadings } from '../data/mockData';

export default function MeterReadingsPage() {
  return (
    <PageSection
      title="Catat Meter"
      description="Mendukung input bulanan oleh petugas lapangan dan review oleh operator desa."
      action={<button className="primary-button">Input Meter Baru</button>}
    >
      <DataTable
        columns={[
          { key: 'customer', label: 'Pelanggan' },
          { key: 'month', label: 'Bulan' },
          { key: 'previous', label: 'Meter Lalu' },
          { key: 'current', label: 'Meter Kini' },
          { key: 'usage', label: 'Pemakaian m3' },
          { key: 'officer', label: 'Petugas' },
        ]}
        rows={meterReadings}
      />
    </PageSection>
  );
}
