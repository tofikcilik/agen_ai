# Panduan Tahap Demi Tahap di VM

Panduan ini khusus untuk lingkungan Anda:

- Ubuntu sudah terpasang
- Docker sudah terpasang
- Nginx Proxy Manager sudah ada di `panel.pelayanan.id`
- Portainer sudah ada di `portainer.pelayanan.id`
- Domain aplikasi:
  - Frontend: `app.pelayanan.id`
  - Backend API: `api.pelayanan.id`

## 1. Siapkan Struktur Folder

Login ke VM lalu jalankan:

```bash
sudo mkdir -p /opt/workspace/airbersih/{repo,backend,frontend}
sudo chown -R $USER:$USER /opt/workspace/airbersih
```

Struktur yang dipakai:

- `/opt/workspace/airbersih/repo/agen_ai` untuk source Git
- `/opt/workspace/airbersih/backend` untuk runtime Laravel
- `/opt/workspace/airbersih/frontend` disiapkan sebagai folder kerja frontend bila nanti dibutuhkan

## 2. Clone Repo GitHub

```bash
cd /opt/workspace/airbersih/repo
git clone https://github.com/tofikcilik/agen_ai.git
```

## 3. Salin File Deploy ke Root Workspace

```bash
cd /opt/workspace/airbersih
cp repo/agen_ai/deploy/vm/docker-compose.yml .
cp repo/agen_ai/deploy/vm/.env.example .env
cp repo/agen_ai/deploy/vm/deploy.sh .
chmod +x deploy.sh
```

## 4. Isi File Environment

Edit file:

```bash
nano /opt/workspace/airbersih/.env
```

Nilai yang perlu Anda pastikan:

- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `APP_URL=https://api.pelayanan.id`
- `SESSION_DOMAIN=.pelayanan.id`
- `SANCTUM_STATEFUL_DOMAINS=app.pelayanan.id`
- `VITE_API_BASE_URL=https://api.pelayanan.id/api`

## 5. Jalankan Stack Pertama Kali

```bash
cd /opt/workspace/airbersih
docker compose up -d --build
```

Saat pertama kali jalan:

- MySQL akan dibuat
- Laravel runtime akan dibuat di `/opt/workspace/airbersih/backend`
- migration dan seed demo akan dijalankan
- frontend React akan dibuild production

## 6. Pastikan Network Proxy Tersedia

Stack deploy VM sekarang sudah dirancang untuk join ke network Docker eksternal bernama `proxy`, supaya Nginx Proxy Manager bisa langsung meneruskan trafik ke nama container tanpa publish port tambahan.

Pastikan network `proxy` sudah ada:

```bash
docker network ls
docker network create proxy
```

Jika network `proxy` sudah ada, perintah create akan gagal dengan aman dan bisa diabaikan.

## 7. Jalankan Ulang Stack

```bash
cd /opt/workspace/airbersih
docker compose up -d --build
```

Kalau ingin memastikan network sudah menempel:

```bash
docker compose ps
docker network inspect proxy
```

## 8. Buat Proxy Host di Nginx Proxy Manager

Di NPM buat dua Proxy Host:

- `app.pelayanan.id` -> `http://airbersih-frontend:80`
- `api.pelayanan.id` -> `http://airbersih-backend:8000`

Pengaturan ringkas:

- Scheme: `http`
- Forward Hostname/IP: nama container tujuan di network `proxy`
- SSL: aktifkan Let’s Encrypt untuk masing-masing domain

## 9. Preview Setelah Ada Perubahan di GitHub

Setiap kali ada perubahan kode baru:

```bash
cd /opt/workspace/airbersih
./deploy.sh
```

Script ini akan:

- masuk ke repo git
- `git pull`
- build ulang image
- restart container

## 10. Opsi Auto Deploy dari GitHub

Repo sudah saya siapkan dengan workflow:

- `.github/workflows/deploy-vm.yml`

Agar aktif, isi GitHub Secrets berikut:

- `VM_HOST`
- `VM_USERNAME`
- `VM_SSH_KEY`

Lalu setiap push ke `main`, GitHub Actions akan SSH ke VM dan menjalankan:

```bash
cd /opt/workspace/airbersih
./deploy.sh
```

Jika SSH VM Anda tidak memakai port `22`, ubah file workflow `.github/workflows/deploy-vm.yml` lalu tambahkan parameter `port`.

## 11. Verifikasi

Cek container:

```bash
cd /opt/workspace/airbersih
docker compose ps
```

Cek log:

```bash
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f mysql
```

Test API langsung dari host:

```bash
docker exec -it nginx-proxy-manager curl http://airbersih-backend:8000/api/auth/me
```

Test frontend dari network yang sama:

```bash
docker exec -it nginx-proxy-manager curl -I http://airbersih-frontend:80
```

## 12. Rekomendasi Praktis

Untuk VM Anda, pola paling enak adalah:

- Git source di `/opt/workspace/airbersih/repo/agen_ai`
- Deploy file di `/opt/workspace/airbersih`
- Backend runtime di `/opt/workspace/airbersih/backend`
- Stack join ke network Docker eksternal `proxy`
- NPM meneruskan `app.pelayanan.id` ke `airbersih-frontend:80`
- NPM meneruskan `api.pelayanan.id` ke `airbersih-backend:8000`

Pola ini paling mudah dirawat saat ada update dari GitHub dan paling cocok dengan Portainer maupun NPM.
