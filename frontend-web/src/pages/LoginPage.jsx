import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const roles = [
  { value: 'administrator', label: 'Root Administrator', note: 'Kontrol penuh semua wilayah dan modul.' },
  { value: 'kecamatan', label: 'Admin Kecamatan', note: 'Pantau desa, pelanggan, tagihan, pembayaran, dan keluhan.' },
  { value: 'desa', label: 'Admin Desa', note: 'Kelola operasional pelayanan desa.' },
  { value: 'petugas', label: 'Petugas Lapangan', note: 'Catat meter dan pembayaran lapangan.' },
];

export default function LoginPage() {
  const [role, setRole] = useState('administrator');
  const { login } = useAuth();
  const navigate = useNavigate();

  function handleSubmit(event) {
    event.preventDefault();
    login(role);
    navigate('/');
  }

  return (
    <div className="login-modern-page">
      <section className="login-hero-panel">
        <div className="brand-mark">AB</div>
        <p className="eyebrow">Sistem Pengelolaan Air Bersih</p>
        <h1>Dashboard operasional air bersih yang siap dipantau lintas wilayah.</h1>
        <p>Kelola pelanggan, desa, meter, tagihan, pembayaran, laporan, dan keluhan dalam satu ruang kerja bersih.</p>
      </section>

      <form className="login-modern-card" onSubmit={handleSubmit}>
        <p className="eyebrow">Masuk aplikasi</p>
        <h2>Pilih role kerja</h2>
        <p className="muted">Mode demo untuk meninjau alur fitur sebelum integrasi akun final.</p>

        <div className="role-option-grid">
          {roles.map((item) => (
            <button
              className={`role-option ${role === item.value ? 'active' : ''}`}
              key={item.value}
              type="button"
              onClick={() => setRole(item.value)}
            >
              <strong>{item.label}</strong>
              <small>{item.note}</small>
            </button>
          ))}
        </div>

        <button className="primary-button login-submit" type="submit">Masuk ke Dashboard</button>
      </form>
    </div>
  );
}
