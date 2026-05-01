# Docker dan Import ke VM GCP

Dokumen ini menyiapkan cara paling praktis untuk menjalankan repo ini di Docker pada VM Compute Engine.

Jika Anda memakai Nginx Proxy Manager dan domain terpisah `app.` serta `api.`, gunakan panduan yang lebih spesifik di `docs/vm-deploy-step-by-step.md`.

## Arsitektur Runtime

- `mysql`: database MySQL 8
- `backend`: container PHP 8.3 yang otomatis membuat runtime Laravel penuh saat pertama kali start
- `gateway`: Nginx sebagai web server frontend statis sekaligus reverse proxy ke backend API

Catatan penting:

- Folder `backend/` di repo ini adalah source domain Laravel-style, belum full instalasi Laravel.
- Saat Docker backend pertama kali hidup, entrypoint akan membuat runtime Laravel lengkap di folder host `backend-runtime/`, lalu menyalin source domain dari `backend/` ke runtime tersebut.
- Frontend React dibuild menjadi aset statis saat image gateway dibuat.
- Backend tidak dipublish langsung ke internet; trafik masuk lewat Nginx.
- Mobile React Native tetap dijalankan terpisah dan tidak dicontainerkan di stack ini.

## Prasyarat di VM GCP

Gunakan Ubuntu 22.04 atau 24.04.

1. Login ke VM.
2. Install Docker dan Docker Compose plugin:

```bash
sudo apt-get update
sudo apt-get install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo \"$VERSION_CODENAME\") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin git
sudo usermod -aG docker $USER
newgrp docker
```

## Import Repo ke VM

Ada dua pilihan:

### Opsi 1: Clone langsung dari GitHub

```bash
git clone https://github.com/tofikcilik/agen_ai.git
cd agen_ai
```

### Opsi 2: Copy dari mesin lain ke VM

Di mesin lokal:

```bash
tar -czf agen_ai.tar.gz agen_ai
gcloud compute scp agen_ai.tar.gz NAMA_VM:~ --zone=ZONE_VM
```

Di VM:

```bash
tar -xzf agen_ai.tar.gz
cd agen_ai
```

## Menjalankan Docker

Di root repo:

```bash
docker compose up -d --build
```

Saat pertama kali jalan:

- MySQL dibuat otomatis
- Laravel runtime dibuat di `backend-runtime/`
- Sanctum di-install otomatis
- Migration dan seeder dijalankan otomatis
- Frontend dibuild ke mode production lalu disajikan oleh Nginx

## Akses Aplikasi

- Frontend Web: `http://IP_VM`
- Backend API: `http://IP_VM/api`

## Demo Login

- Kecamatan: `kecamatan@airbersih.test` / `password`
- Desa: `desa@airbersih.test` / `password`
- Petugas: `petugas@airbersih.test` / `password`

## Port yang Perlu Dibuka di Firewall GCP

Izinkan TCP:

- `80` untuk frontend dan reverse proxy API
- `443` jika nanti Anda tambah SSL
- `3306` hanya jika Anda memang perlu akses MySQL dari luar VM

Contoh:

```bash
gcloud compute firewall-rules create airbersih-web \
  --allow tcp:80,tcp:443 \
  --target-tags airbersih-server
```

Lalu tambahkan network tag `airbersih-server` ke VM Anda.

## Perintah Operasional

Lihat log:

```bash
docker compose logs -f
```

Restart:

```bash
docker compose down
docker compose up -d
```

Reset database:

```bash
docker compose down -v
rm -rf backend-runtime
docker compose up -d --build
```

## Folder Penting Setelah Runtime Terbentuk

- `backend/`: source domain yang Anda edit
- `backend-runtime/`: instalasi Laravel runtime hasil bootstrap Docker

Jika Anda mengubah file domain di `backend/` dan ingin runtime ikut sinkron penuh, jalankan:

```bash
docker compose down
rm -f backend-runtime/.airbersih_bootstrapped
docker compose up -d --build
```

## Catatan Pengembangan

- Untuk development jangka panjang, Anda bisa lanjut memindahkan semua source dari `backend/` ke `backend-runtime/` setelah bootstrap pertama, lalu menjadikannya instalasi Laravel utama.
- Pendekatan saat ini sengaja dipilih agar repo yang sekarang tetap bisa hidup di Docker tanpa harus menyusun seluruh skeleton Laravel secara manual di container ini.
- Jika Anda ingin mode production yang lebih keras lagi, langkah berikutnya adalah menambah SSL certificate, memindahkan backend ke `php-fpm`, dan menjalankan queue worker terpisah.
