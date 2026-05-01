const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'https://api.pelayanan.id/api';

function getToken() {
  return window.localStorage.getItem('abm_token');
}

async function parseResponse(response) {
  const text = await response.text();
  const data = text ? JSON.parse(text) : null;

  if (!response.ok) {
    const error = new Error(data?.message || 'Permintaan gagal diproses.');
    error.status = response.status;
    error.errors = data?.errors || null;
    throw error;
  }

  return data;
}

export async function apiRequest(path, options = {}) {
  const token = getToken();
  const headers = {
    Accept: 'application/json',
    ...(options.body ? { 'Content-Type': 'application/json' } : {}),
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...(options.headers || {}),
  };

  const response = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers,
    body: options.body ? JSON.stringify(options.body) : undefined,
  });

  return parseResponse(response);
}

export const api = {
  get: (path) => apiRequest(path),
  post: (path, body) => apiRequest(path, { method: 'POST', body }),
  put: (path, body) => apiRequest(path, { method: 'PUT', body }),
  patch: (path, body) => apiRequest(path, { method: 'PATCH', body }),
  delete: (path) => apiRequest(path, { method: 'DELETE' }),
};

export function getValidationMessage(error) {
  if (!error?.errors) return error?.message || 'Terjadi kesalahan.';
  return Object.values(error.errors).flat().join(' ');
}
