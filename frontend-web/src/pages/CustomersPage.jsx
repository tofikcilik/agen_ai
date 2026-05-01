import PageSection from '../components/PageSection';

const customers = [
  {
    customer_number: 'DES001_000001',
    name: 'Siti Aminah',
    phone: '081234560001',
    rt: '001',
    rw: '002',
    village: 'Sumber Jaya',
    district: 'Sukamaju',
    coordinates: '-6.214620, 106.845130',
    meter_number: 'MTR-1001',
    status: 'Aktif',
  },
  {
    customer_number: 'DES001_000002',
    name: 'Rahmat Hidayat',
    phone: '081234560002',
    rt: '003',
    rw: '001',
    village: 'Sumber Jaya',
    district: 'Sukamaju',
    coordinates: '-6.215100, 106.846200',
    meter_number: 'MTR-1002',
    status: 'Aktif',
  },
  {
    customer_number: 'DES002_000001',
    name: 'Dewi Lestari',
    phone: '081234560003',
    rt: '002',
    rw: '004',
    village: 'Harapan Baru',
    district: 'Sukamaju',
    coordinates: '-6.217800, 106.842900',
    meter_number: 'MTR-1003',
    status: 'Nonaktif',
  },
];

export default function CustomersPage() {
  return (
    <div className="stack">
      <PageSection title="Identitas Pelanggan" description="Nomor pelanggan dibuat otomatis dengan format kodedesa_nomorpelanggan.">
        <div className="metric-row">
          <div><span>Total Pelanggan</span><strong>{customers.length}</strong></div>
          <div><span>Pelanggan Aktif</span><strong>{customers.filter((item) => item.status === 'Aktif').length}</strong></div>
          <div><span>Desa Terdata</span><strong>{new Set(customers.map((item) => item.village)).size}</strong></div>
        </div>
      </PageSection>

      <PageSection title="Form Pelanggan" description="Desa dan kecamatan otomatis mengikuti wilayah yang dipilih. Nomor pelanggan tidak diisi manual.">
        <div className="form-grid">
          <label>Nama<input placeholder="Nama pelanggan" /></label>
          <label>No. HP<input placeholder="08xxxxxxxxxx" /></label>
          <label>RT<input placeholder="001" /></label>
          <label>RW<input placeholder="002" /></label>
          <label>Desa<select><option>Sumber Jaya</option><option>Harapan Baru</option></select></label>
          <label>Kecamatan<input placeholder="Otomatis" disabled /></label>
          <label>Latitude<input placeholder="-6.214620" /></label>
          <label>Longitude<input placeholder="106.845130" /></label>
          <label>Nomor Meter<input placeholder="Opsional" /></label>
          <label>Alamat Detail<input placeholder="Jalan, dusun, patokan rumah" /></label>
          <button className="primary-button">Simpan Pelanggan</button>
        </div>
      </PageSection>

      <PageSection title="Data Pelanggan per Desa" description="Tabel identitas pelanggan minimal sesuai pondasi database.">
        <div className="table-wrap">
          <table className="data-table">
            <thead>
              <tr>
                <th>Nomor</th><th>Nama</th><th>No. HP</th><th>RT/RW</th><th>Desa</th><th>Kecamatan</th><th>Koordinat</th><th>Meter</th><th>Status</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              {customers.map((item) => (
                <tr key={item.customer_number}>
                  <td>{item.customer_number}</td>
                  <td>{item.name}</td>
                  <td>{item.phone}</td>
                  <td>{item.rt}/{item.rw}</td>
                  <td>{item.village}</td>
                  <td>{item.district}</td>
                  <td>{item.coordinates}</td>
                  <td>{item.meter_number}</td>
                  <td>{item.status}</td>
                  <td><button className="ghost-button">Edit</button> <button className="ghost-button danger-text">Hapus</button></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </PageSection>
    </div>
  );
}
