import { NavLink, Outlet } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const menu = [
  { to: '/', label: 'Dashboard', roles: ['kecamatan', 'desa', 'petugas_lapangan'] },
  { to: '/pelanggan', label: 'Pelanggan', roles: ['kecamatan', 'desa'] },
  { to: '/catat-meter', label: 'Catat Meter', roles: ['desa', 'petugas_lapangan'] },
  { to: '/tagihan', label: 'Tagihan', roles: ['kecamatan', 'desa'] },
  { to: '/pembayaran', label: 'Pembayaran', roles: ['desa', 'petugas_lapangan'] },
  { to: '/keluhan', label: 'Keluhan', roles: ['kecamatan', 'desa', 'petugas_lapangan'] },
  { to: '/laporan-keuangan', label: 'Laporan Keuangan', roles: ['kecamatan', 'desa'] },
];

export default function AppLayout() {
  const { user, logout } = useAuth();

  return (
    <div className="shell">
      <aside className="sidebar">
        <div>
          <p className="eyebrow">Air Bersih</p>
          <h1>Management</h1>
          <p className="muted">{user?.name}</p>
          <span className="role-chip">{user?.role}</span>
        </div>
        <nav className="menu">
          {menu.filter((item) => item.roles.includes(user?.role)).map((item) => (
            <NavLink key={item.to} to={item.to} end={item.to === '/'}>
              {item.label}
            </NavLink>
          ))}
        </nav>
        <button className="secondary-button" onClick={logout}>Keluar</button>
      </aside>
      <main className="content">
        <Outlet />
      </main>
    </div>
  );
}
