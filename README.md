# Air Bersih Management

Monorepo aplikasi pengelolaan air bersih untuk kecamatan, desa, dan petugas lapangan.

## Struktur

- `backend/`: REST API bergaya Laravel + Sanctum + MySQL
- `frontend-web/`: React Vite untuk operator kecamatan dan desa
- `mobile-field/`: React Native untuk petugas lapangan Android
- `docs/`: arsitektur, setup, dan dokumentasi endpoint

## Modul Inti

1. Manajemen pelanggan air bersih
2. Pencatatan meter air bulanan
3. Generate tagihan otomatis dari pemakaian meter
4. Penagihan dan penerimaan pembayaran
5. Gangguan dan keluhan pelanggan
6. Laporan keuangan desa dan kecamatan
7. Dashboard monitoring operasional

## Catatan Implementasi

Container ini tidak menyediakan `php`, `composer`, dan `docker`, sehingga saya tidak bisa menyalakan stack runtime langsung dari environment kerja ini. Namun source code, stack Docker production-style, dan dokumentasi deploy sudah disiapkan agar bisa langsung dijalankan di VM development atau GCP.

Lihat [docs/architecture.md](/workspace/agen_ai/docs/architecture.md) dan [docs/setup.md](/workspace/agen_ai/docs/setup.md).

## Jalankan dengan Docker

Stack production-style untuk VM GCP sudah disiapkan di root repo:

```bash
docker compose up -d --build
```

Panduan lengkapnya ada di [docs/docker-gcp-vm.md](/workspace/agen_ai/docs/docker-gcp-vm.md).

## Deploy VM

Untuk setup yang cocok dengan Nginx Proxy Manager, Portainer, dan domain:

- `app.pelayanan.id`
- `api.pelayanan.id`

lihat [docs/vm-deploy-step-by-step.md](/workspace/agen_ai/docs/vm-deploy-step-by-step.md).
