# Air Bersih Management

Monorepo aplikasi pengelolaan air bersih untuk kecamatan, desa, dan petugas lapangan.

## Struktur

- `backend/`: REST API Laravel, Sanctum, dan MySQL.
- `frontend-web/`: React Vite untuk operator web.
- `mobile-field/`: struktur aplikasi mobile petugas lapangan.
- `docker/`: konfigurasi container.
- `deploy/vm/`: konfigurasi preview VM.
- `docs/`: dokumentasi teknis dan panduan deploy.

## Modul Inti

1. Manajemen pelanggan air bersih.
2. Pencatatan meter bulanan.
3. Generate tagihan dari pemakaian meter.
4. Penagihan dan penerimaan pembayaran.
5. Gangguan dan keluhan pelanggan.
6. Laporan keuangan desa dan kecamatan.
7. Dashboard monitoring operasional.

## Deploy VM

Gunakan compose khusus VM dari root repository:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
docker compose -f deploy/vm/docker-compose.yml up -d --build
```

Runtime Laravel preview ditempatkan di `/opt/workspace/airbersih/backend`. Source code tetap berada di repository ini.
