import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { api } from '../lib/api';

const AuthContext = createContext(null);

function loadSavedUser() {
  try {
    const savedUser = window.localStorage.getItem('abm_user');
    return savedUser ? JSON.parse(savedUser) : null;
  } catch {
    return null;
  }
}

export function AuthProvider({ children }) {
  const [user, setUser] = useState(loadSavedUser);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const token = window.localStorage.getItem('abm_token');
    if (!token || user) return;

    setLoading(true);
    api.get('/auth/me')
      .then((profile) => {
        window.localStorage.setItem('abm_user', JSON.stringify(profile));
        setUser(profile);
      })
      .catch(() => {
        window.localStorage.removeItem('abm_user');
        window.localStorage.removeItem('abm_token');
        setUser(null);
      })
      .finally(() => setLoading(false));
  }, [user]);

  const value = useMemo(() => ({
    user,
    loading,
    login: async ({ email, password }) => {
      const response = await api.post('/auth/login', {
        email,
        password,
        device_name: 'frontend-web',
      });

      window.localStorage.setItem('abm_token', response.token);
      window.localStorage.setItem('abm_user', JSON.stringify(response.user));
      setUser(response.user);
      return response.user;
    },
    logout: async () => {
      try {
        await api.post('/auth/logout', {});
      } catch {
        // Token lokal tetap dibersihkan walaupun request logout gagal.
      }
      window.localStorage.removeItem('abm_user');
      window.localStorage.removeItem('abm_token');
      setUser(null);
    },
  }), [user, loading]);

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  return useContext(AuthContext);
}
