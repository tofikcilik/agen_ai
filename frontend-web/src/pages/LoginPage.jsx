import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function LoginPage() {
  const [role, setRole] = useState('kecamatan');
  const { login } = useAuth();
  const navigate = useNavigate();

  function handleSubmit(event) {
    event.preventDefault();
    login(role);
    navigate('/');
  }

  return (
    <div className="login-page">
      <form className="login-card" onSubmit={handleSubmit}>
        <p className="eyebrow">Sistem Pengelolaan Air Bersih</p>
        <h1>Masuk ke Dashboard</h1>
        <p className="muted">Pilih role demo untuk melihat alur kerja aplikasi web.</p>
        <label>
          Role
          <select value={role} onChange={(event) => setRole(event.target.value)}>
            <option value="kecamatan">Kecamatan</option>
            <option value="desa">Desa</option>
            <option value="petugas">Petugas Lapangan</option>
          </select>
        </label>
        <button className="primary-button" type="submit">Masuk</button>
      </form>
    </div>
  );
}
