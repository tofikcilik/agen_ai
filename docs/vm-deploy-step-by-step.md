# Deploy Preview Air Bersih Management di VM GCP

Panduan ini mengikuti kondisi VM saat ini:

- Nginx Proxy Manager ada di `panel.pelayanan.id`
- Portainer ada di `portainer.pelayanan.id`
- Frontend preview memakai `app.pelayanan.id`
- Backend API preview memakai `api.pelayanan.id`
- Folder kerja utama: `/opt/workspace/airbersih`
- Source GitHub ditaruh di: `/opt/workspace/airbersih/repo/agen_ai`
- Runtime Laravel ditaruh di: `/opt/workspace/airbersih/backend`

## 1. Buat folder kerja

```bash
sudo mkdir -p /opt/workspace/airbersih/{repo,backend,frontend}
sudo chown -R $USER:$USER /opt/workspace/airbersih
```

## 2. Clone repo GitHub

```bash
cd /opt/workspace/airbersih/repo
git clone https://github.com/tofikcilik/agen_ai.git
```

Jika repo sudah ada di VM, cukup update:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
git pull --ff-only
```

## 3. Jalankan stack Docker dari file khusus VM

File compose khusus VM ada di:

```text
deploy/vm/docker-compose.yml
```

Jalankan:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
docker compose -f deploy/vm/docker-compose.yml up -d --build
```

Cek container:

```bash
docker compose -f deploy/vm/docker-compose.yml ps
```

Cek log:

```bash
docker compose -f deploy/vm/docker-compose.yml logs -f
```

## 4. Port yang dipakai

Stack VM mem-publish port berikut ke host:

| Service | Host Port | Container Port | Domain |
|---|---:|---:|---|
| Frontend | `18080` | `80` | `app.pelayanan.id` |
| Backend API | `18000` | `8000` | `api.pelayanan.id` |

Database MySQL tidak dipublish ke internet. Database hanya dipakai internal container.

## 5. Setting Nginx Proxy Manager

Buka NPM di:

```text
https://panel.pelayanan.id
```

Buat Proxy Host untuk frontend:

- Domain Names: `app.pelayanan.id`
- Scheme: `http`
- Forward Hostname/IP: IP VM atau nama host Docker yang dapat dijangkau NPM
- Forward Port: `18080`
- Aktifkan Websockets Support jika tersedia
- SSL: Request New SSL Certificate, aktifkan Force SSL

Buat Proxy Host untuk backend:

- Domain Names: `api.pelayanan.id`
- Scheme: `http`
- Forward Hostname/IP: IP VM atau nama host Docker yang dapat dijangkau NPM
- Forward Port: `18000`
- Aktifkan Websockets Support jika tersedia
- SSL: Request New SSL Certificate, aktifkan Force SSL

## 6. Test akses

Frontend:

```text
https://app.pelayanan.id
```

Backend API:

```text
https://api.pelayanan.id/api
```

Health check sederhana:

```bash
curl -I https://app.pelayanan.id
curl -I https://api.pelayanan.id/api
```

## 7. Update ketika ada perubahan dari GitHub

Setiap ada perubahan source code di GitHub, jalankan:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
git pull --ff-only
docker compose -f deploy/vm/docker-compose.yml up -d --build
```

Untuk melihat hasilnya:

```bash
docker compose -f deploy/vm/docker-compose.yml ps
docker compose -f deploy/vm/docker-compose.yml logs -f
```

## 8. Catatan struktur folder

Struktur yang disarankan:

```text
/opt/workspace/airbersih/
├── backend/              # runtime Laravel container
├── frontend/             # folder cadangan frontend/deploy
└── repo/
    └── agen_ai/          # source GitHub
        ├── backend/
        ├── frontend-web/
        ├── mobile-field/
        ├── docker/
        ├── deploy/vm/
        └── docs/
```

Jangan clone repo langsung ke `/opt/workspace/airbersih/backend`, karena folder itu dipakai sebagai runtime Laravel oleh container.

## 9. Troubleshooting cepat

Jika frontend tidak terbuka:

```bash
docker logs airbersih-frontend
```

Jika API tidak terbuka:

```bash
docker logs airbersih-backend
```

Jika database belum siap:

```bash
docker logs airbersih-mysql
```

Jika ingin rebuild bersih:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
docker compose -f deploy/vm/docker-compose.yml down
docker compose -f deploy/vm/docker-compose.yml up -d --build
```

Jika ingin reset database preview:

```bash
cd /opt/workspace/airbersih/repo/agen_ai
docker compose -f deploy/vm/docker-compose.yml down -v
docker compose -f deploy/vm/docker-compose.yml up -d --build
```

Perintah reset database akan menghapus data MySQL preview.
