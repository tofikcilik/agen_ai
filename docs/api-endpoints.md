# API Endpoints

Base path: `/api`

## Auth

- `POST /auth/login`
- `POST /auth/logout`
- `GET /auth/me`

## Dashboard

- `GET /dashboard`

## Reference Data

- `GET /districts`
- `GET /villages`
- `POST /villages`
- `PUT /villages/{id}`
- `DELETE /villages/{id}`

## Customers

- `GET /customers`
- `POST /customers`
- `GET /customers/{id}`
- `PUT /customers/{id}`
- `DELETE /customers/{id}`

## Meter Readings

- `GET /meter-readings`
- `GET /meter-readings/monthly?month=YYYY-MM`
- `POST /meter-readings`

Request body:

```json
{
  "customer_id": 1,
  "reading_month": "2026-05",
  "previous_value": 120,
  "current_value": 148,
  "notes": "Meter normal"
}
```

## Bills

- `GET /bills`
- `GET /bills/{id}`
- `POST /bills/generate`

Request body:

```json
{
  "reading_month": "2026-05",
  "due_date": "2026-05-25"
}
```

## Payments

- `GET /payments`
- `POST /payments`

Request body:

```json
{
  "bill_id": 10,
  "payment_date": "2026-05-10",
  "amount_paid": 98000,
  "payment_method": "cash",
  "reference_number": "KW-2026-0001",
  "notes": "Lunas di tempat"
}
```

## Complaints

- `GET /complaints`
- `POST /complaints`
- `PATCH /complaints/{id}/status`

## Reports

- `GET /reports/financial-summary`
- `GET /reports/arrears`
- `GET /reports/usage`

## Hak Akses

- `administrator`: kontrol penuh lintas kecamatan dan desa
- `kecamatan`: CRUD desa di bawah kecamatannya, pelanggan, tagihan, dan laporan
- `desa`: pelanggan, meter, tagihan, pembayaran, keluhan, laporan desa sendiri
- `petugas_lapangan`: input meter, pembayaran, dan update keluhan untuk wilayah desa sendiri

## Catatan Query

- Daftar `customers`, `meter-readings`, `bills`, `payments`, dan `complaints` menerima `?per_page=` untuk mengatur jumlah data per halaman.
- Semua endpoint daftar di-scope otomatis sesuai role pengguna yang sedang login.
