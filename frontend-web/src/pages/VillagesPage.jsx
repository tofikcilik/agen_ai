import PageSection from '../components/PageSection';

const villages = [
  { code: 'DES001', name: 'Sumber Jaya', district: 'Sukamaju', customers: 186, arrears: 14 },
  { code: 'DES002', name: 'Harapan Baru', district: 'Sukamaju', customers: 132, arrears: 9 },
  { code: 'DES003', name: 'Tirta Makmur', district: 'Sukamaju', customers: 98, arrears: 6 },
];

export default function VillagesPage() {
  return (
    <div className="stack">
      <PageSection title="Daftar Desa" description="CRUD desa di bawah wilayah kecamatan, termasuk ringkasan pelanggan dan tunggakan.">
        <div className="metric-row">
          <div><span>Total Desa</span><strong>{villages.length}</strong></div>
          <div><span>Total Pelanggan</span><strong>{villages.reduce((sum, item) => sum + item.customers, 0)}</strong></div>
          <div><span>Tunggakan Aktif</span><strong>{villages.reduce((sum, item) => sum + item.arrears, 0)}</strong></div>
        </div>
      </PageSection>

      <PageSection title="Form Desa" description="Siapkan kode desa yang menjadi dasar nomor pelanggan otomatis.">
        <div className="form-grid">
          <label>Kode Desa<input placeholder="DES001" /></label>
          <label>Nama Desa<input placeholder="Sumber Jaya" /></label>
          <label>Kecamatan<input placeholder="Sukamaju" /></label>
          <button className="primary-button">Simpan Desa</button>
        </div>
      </PageSection>

      <PageSection title="Data Desa" description="Daftar desa yang dapat dikelola admin root dan kecamatan.">
        <div className="table-wrap">
          <table className="data-table">
            <thead>
              <tr><th>Kode</th><th>Desa</th><th>Kecamatan</th><th>Pelanggan</th><th>Tunggakan</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              {villages.map((item) => (
                <tr key={item.code}>
                  <td>{item.code}</td>
                  <td>{item.name}</td>
                  <td>{item.district}</td>
                  <td>{item.customers}</td>
                  <td>{item.arrears}</td>
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
