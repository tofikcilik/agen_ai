# Setup Guide

## Backend

1. Pastikan tersedia PHP 8.2+, Composer, dan MySQL.
2. Inisialisasi project Laravel baru atau salin folder `backend/` ini ke instalasi Laravel.
3. Install dependensi utama:
   - `laravel/framework`
   - `laravel/sanctum`
4. Pastikan alias middleware `role` mengacu ke `App\Http\Middleware\EnsureUserHasRole`.
5. Buat `.env`, atur koneksi MySQL, lalu jalankan:

```bash
php artisan migrate --seed
php artisan serve
```

## Frontend Web

```bash
cd frontend-web
cp .env.example .env
npm install
npm run dev
```

## Mobile Field

```bash
cd mobile-field
npm install
npm run android
```

## Demo User

- Kecamatan: `kecamatan@airbersih.test` / `password`
- Desa: `desa@airbersih.test` / `password`
- Petugas: `petugas@airbersih.test` / `password`
