# Arsitektur Air Bersih Management

## Stack

- Backend: Laravel REST API + Laravel Sanctum
- Database: MySQL
- Web: React + Vite
- Mobile Android: React Native

## Keputusan Desain

- Pendekatan `API-first` agar web dan mobile memakai kontrak backend yang sama.
- Role disimpan sebagai data bisnis di tabel `roles` dan direlasikan ke `users`.
- Struktur wilayah dibuat bertingkat: `districts -> villages -> customers`.
- Semua transaksi operasional utama mengalir dari `meter_readings -> bills -> payments`.
- Laporan keuangan memakai endpoint agregasi sehingga mudah dipakai dashboard, ekspor, atau aplikasi mobile di tahap berikutnya.

## Komponen Utama

### Backend

- Auth Sanctum dan middleware role
- Master data wilayah, pelanggan, dan petugas
- Pembacaan meter bulanan
- Generator tagihan berdasarkan selisih meter
- Pembayaran dan pencatatan penagihan
- Keluhan/gangguan pelanggan
- Dashboard dan laporan keuangan

### Frontend Web

- Login
- Dashboard role-based
- Halaman pelanggan
- Halaman pencatatan meter
- Halaman tagihan
- Halaman pembayaran
- Halaman keluhan
- Halaman laporan keuangan

### Mobile Field

- Login petugas
- Daftar pelanggan lapangan
- Input meter
- Input pembayaran
- Keluhan lapangan

## Struktur Folder

```text
air-bersih-management/
  backend/
  frontend-web/
  mobile-field/
  docs/
```

## Model Data Inti

- `roles`
- `users`
- `districts`
- `villages`
- `customers`
- `meter_readings`
- `bills`
- `payments`
- `complaints`

## Strategi Pengembangan Lanjutan

1. Jalankan `composer create-project laravel/laravel backend-runtime` di mesin yang memiliki PHP/Composer lalu pindahkan file domain ini ke dalam instalasi Laravel final.
2. Sambungkan frontend dan mobile ke backend setelah `.env` dan database aktif.
3. Tambahkan test feature Laravel, export Excel/PDF, dan sinkronisasi offline untuk petugas lapangan.
