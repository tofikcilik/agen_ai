import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { getValidationMessage } from '../lib/api';
import { useAuth } from '../contexts/AuthContext';

const demoAccounts = [
  { label: 'Root', email: 'admin@airbersih.test' },
  { label: 'Kecamatan', email: 'kecamatan@airbersih.test' },
  { label: 'Desa', email: 'desa@airbersih.test' },
  { label: 'Petugas', email: 'petugas@airbersih.test' },
];

export default function LoginPage() {
  const [form, setForm] = useState({ email: 'admin@airbersih.test', password: 'password' });
  const [error, setError] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  function updateField(field, value) {
    setForm((current) => ({ ...current, [field]: value }));
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setError('');
    setSubmitting(true);

    try {
      await login(form);
      navigate('/');
    } catch (err) {
      setError(getValidationMessage(err));
    } finally {
      setSubmitting(false);
    }
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
        <h2>Login operator</h2>
        <p className="muted">Gunakan akun demo dari seeder untuk masuk ke API Laravel Sanctum.</p>

        <label>
          Email
          <input value={form.email} onChange={(event) => updateField('email', event.target.value)} type="email" />
        </label>
        <label>
          Password
          <input value={form.password} onChange={(event) => updateField('password', event.target.value)} type="password" />
        </label>

        <div className="role-option-grid">
          {demoAccounts.map((account) => (
            <button
              className={`role-option ${form.email === account.email ? 'active' : ''}`}
              key={account.email}
              type="button"
              onClick={() => setForm({ email: account.email, password: 'password' })}
            >
              <strong>{account.label}</strong>
              <small>{account.email}</small>
            </button>
          ))}
        </div>

        {error ? <div className="alert danger">{error}</div> : null}
        <button className="primary-button login-submit" disabled={submitting} type="submit">
          {submitting ? 'Masuk...' : 'Masuk ke Dashboard'}
        </button>
      </form>
    </div>
  );
}
