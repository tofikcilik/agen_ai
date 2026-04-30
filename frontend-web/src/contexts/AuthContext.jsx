import { createContext, useContext, useState } from 'react';

const AuthContext = createContext(null);

const demoUsers = {
  kecamatan: { name: 'Operator Kecamatan', role: 'kecamatan' },
  desa: { name: 'Operator Desa', role: 'desa' },
  petugas: { name: 'Petugas Lapangan', role: 'petugas_lapangan' },
};

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => {
    const savedUser = window.localStorage.getItem('abm_user');
    return savedUser ? JSON.parse(savedUser) : null;
  });

  const value = {
    user,
    login: (role) => {
      const selectedUser = demoUsers[role] ?? demoUsers.kecamatan;
      window.localStorage.setItem('abm_user', JSON.stringify(selectedUser));
      window.localStorage.setItem('abm_token', 'demo-token');
      setUser(selectedUser);
    },
    logout: () => {
      window.localStorage.removeItem('abm_user');
      window.localStorage.removeItem('abm_token');
      setUser(null);
    },
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  return useContext(AuthContext);
}
