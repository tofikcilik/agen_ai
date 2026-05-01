import PageSection from '../components/PageSection';

const complaints = [
  {
    reporter_name: 'Siti Aminah',
    reporter_phone: '081234560001',
    title: 'Air mati sejak pagi',
    description: 'Aliran air tidak keluar sejak pukul 06.00.',
    disturbance_location: 'RT 001/RW 002 dekat musala Sumber Jaya',
    village: 'Sumber Jaya',
    coordinates: '-6.214620, 106.845130',
    status: 'Baru',
  },
  {
    reporter_name: 'Rahmat Hidayat',
    reporter_phone: '081234560002',
    title: 'Kebocoran pipa utama',
    description: 'Air keluar dari sambungan pipa di pinggir jalan.',
    disturbance_location: 'Jalan utama RT 003/RW 001',
    village: 'Sumber Jaya',
    coordinates: '-6.215100, 106.846200',
    status: 'Diproses',
  },
];

export default function ComplaintsPage() {
  return (
    <div className="stack">
      <PageSection title="Keluhan dan Gangguan" description="Data laporan minimal berisi nama, no HP, keluhan/laporan, dan lokasi gangguan.">
        <div className="metric-row">
          <div><span>Total Laporan</span><strong>{complaints.length}</strong></div>
          <div><span>Baru</span><strong>{complaints.filter((item) => item.status === 'Baru').length}</strong></div>
          <div><span>Diproses</span><strong>{complaints.filter((item) => item.status === 'Diproses').length}</strong></div>
        </div>
      </PageSection>

      <PageSection title="Form Keluhan" description="Laporan dapat dibuat oleh pelanggan terdaftar atau warga yang belum tercatat sebagai pelanggan.">
        <div className="form-grid">
          <label>Nama Pelapor<input placeholder="Nama lengkap" /></label>
          <label>No. HP<input placeholder="08xxxxxxxxxx" /></label>
          <label>Desa<select><option>Sumber Jaya</option><option>Harapan Baru</option></select></label>
          <label>Kategori<select><option>Air Mati</option><option>Kebocoran</option><option>Kualitas Air</option><option>Lainnya</option></select></label>
          <label>Keluhan/Laporan<input placeholder="Ringkasan laporan" /></label>
          <label>Lokasi Gangguan<input placeholder="RT/RW, jalan, patokan" /></label>
          <label>Latitude<input placeholder="-6.214620" /></label>
          <label>Longitude<input placeholder="106.845130" /></label>
          <button className="primary-button">Simpan Laporan</button>
        </div>
      </PageSection>

      <PageSection title="Peta Gangguan" description="Koordinat gangguan ditampilkan sebagai titik monitoring wilayah.">
        <div className="map-panel">
          {complaints.map((item) => (
            <div className="map-pin" key={item.title}>
              <strong>{item.village}</strong>
              <span>{item.coordinates}</span>
              <small>{item.title}</small>
            </div>
          ))}
        </div>
      </PageSection>

      <PageSection title="Daftar Keluhan" description="Keluhan seluruh wilayah dapat dipantau oleh root dan kecamatan.">
        <div className="table-wrap">
          <table className="data-table">
            <thead>
              <tr><th>Nama</th><th>No. HP</th><th>Laporan</th><th>Lokasi</th><th>Desa</th><th>Koordinat</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              {complaints.map((item) => (
                <tr key={item.title}>
                  <td>{item.reporter_name}</td>
                  <td>{item.reporter_phone}</td>
                  <td>{item.description}</td>
                  <td>{item.disturbance_location}</td>
                  <td>{item.village}</td>
                  <td>{item.coordinates}</td>
                  <td>{item.status}</td>
                  <td><button className="ghost-button">Tindak Lanjut</button></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </PageSection>
    </div>
  );
}
