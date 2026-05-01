import { NavLink, Outlet } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

const allRoles = ['administrator', 'kecamatan', 'desa', 'petugas_lapangan'];
const adminRoles = ['administrator', 'kecamatan'];

const menu = [
  { to: '/', label: 'Dashboard', roles: allRoles },
  { to: '/desa', label: 'Daftar Desa', roles: adminRoles },
  { to: '/pelanggan', label: 'Pelanggan', roles: ['administrator', 'kecamatan', 'desa'] },
  { to: '/catat-meter', label: 'Catat Meter', roles: ['administrator', 'desa', 'petugas_lapangan'] },
  { to: '/tagihan', label: 'Tagihan', roles: ['administrator', 'kecamatan', 'desa'] },
  { to: '/pembayaran', label: 'Pembayaran', roles: ['administrator', 'kecamatan', 'desa', 'petugas_lapangan'] },
  { to: '/keluhan', label: 'Keluhan & Gangguan', roles: allRoles },
  { to: '/laporan-keuangan', label: 'Laporan Keuangan', roles: ['administrator', 'kecamatan', 'desa'] },
  { to: '/statistik', label: 'Statistik', roles: ['administrator', 'kecamatan'] },
];

export default function AppLayout() {
  const { user, logout } = useAuth();

  return (
    <div className="shell modern-shell">
      <aside className="sidebar modern-sidebar">
        <div>
          <div className="sidebar-brand">
            <span>AB</span>
            <div>
              <p className="eyebrow">Air Bersih</p>
              <h1>Management</h1>
            </div>
          </div>
          <div className="operator-card">
            <p className="muted">Login sebagai</p>
            <strong>{user?.name}</strong>
            <span className="role-chip">{user?.role}</span>
          </div>
        </div>
        <nav className="menu modern-menu">
          {menu.filter((item) => item.roles.includes(user?.role)).map((item) => (
            <NavLink key={item.to} to={item.to} end={item.to === '/'}>
              {item.label}
            </NavLink>
          ))}
        </nav>
        <button className="secondary-button" onClick={logout}>Keluar</button>
      </aside>
      <main className="content modern-content">
        <Outlet />
      </main>
    </div>
  );
}
