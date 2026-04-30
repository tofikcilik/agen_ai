export const dashboardSummary = [
  { label: 'Pelanggan Aktif', value: '1.248', tone: 'primary' },
  { label: 'Pemakaian Bulan Ini', value: '18.420 m3', tone: 'success' },
  { label: 'Tunggakan', value: 'Rp 86,4 Jt', tone: 'warning' },
  { label: 'Keluhan Aktif', value: '23', tone: 'danger' },
];

export const customers = [
  { id: 1, number: 'PLG-0001', name: 'Siti Aminah', village: 'Sumber Jaya', meter: 'MTR-1001', status: 'Aktif' },
  { id: 2, number: 'PLG-0002', name: 'Rahmat Hidayat', village: 'Sumber Jaya', meter: 'MTR-1002', status: 'Aktif' },
  { id: 3, number: 'PLG-0003', name: 'Dewi Lestari', village: 'Harapan Baru', meter: 'MTR-1003', status: 'Nonaktif' },
];

export const meterReadings = [
  { id: 1, customer: 'Siti Aminah', month: '2026-05', previous: 120, current: 148, usage: 28, officer: 'Petugas Lapangan' },
  { id: 2, customer: 'Rahmat Hidayat', month: '2026-05', previous: 90, current: 112, usage: 22, officer: 'Petugas Lapangan' },
];

export const bills = [
  { id: 1, customer: 'Siti Aminah', month: '2026-05', usage: 28, amount: 'Rp 98.000', status: 'Belum Lunas' },
  { id: 2, customer: 'Rahmat Hidayat', month: '2026-05', usage: 22, amount: 'Rp 77.000', status: 'Lunas' },
];

export const payments = [
  { id: 1, customer: 'Rahmat Hidayat', date: '2026-05-10', amount: 'Rp 77.000', method: 'Cash', officer: 'Petugas Lapangan' },
  { id: 2, customer: 'Siti Aminah', date: '2026-05-11', amount: 'Rp 50.000', method: 'Transfer', officer: 'Operator Desa' },
];

export const complaints = [
  { id: 1, customer: 'Siti Aminah', category: 'Air Mati', title: 'Distribusi berhenti', status: 'Diproses' },
  { id: 2, customer: 'Dewi Lestari', category: 'Kebocoran', title: 'Pipa samping rumah bocor', status: 'Baru' },
];

export const reports = [
  { village: 'Sumber Jaya', revenue: 'Rp 23.400.000', arrears: 'Rp 7.600.000', transactions: 168 },
  { village: 'Harapan Baru', revenue: 'Rp 17.800.000', arrears: 'Rp 4.200.000', transactions: 124 },
];
