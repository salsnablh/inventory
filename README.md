# Inventory Salsa Nabillah

Aplikasi inventory berbasis Laravel 9, PostgreSQL Neon, Docker Compose, dan Traefik.

## Service

- `pencatatan`: pencatatan barang, stok masuk, stok keluar.
- `cetak-laporan`: ringkasan inventory dan export CSV.
- `notifikasi-komunikasi`: draft notifikasi stok menipis dan riwayat komunikasi.

## Konfigurasi Neon PostgreSQL

Salin `.env.example` menjadi `.env`, lalu isi kredensial Neon:

```env
DB_CONNECTION=pgsql
DB_HOST=ep-your-neon-host.ap-southeast-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=your-password
DB_SSLMODE=require
```

Jika memakai connection string dari Neon, tetap pecah nilainya ke variabel di atas agar konfigurasi Docker lebih mudah dibaca.

## Menjalankan dengan Docker Compose

```bash
docker compose up -d --build
docker compose exec pencatatan php artisan migrate
```

URL lokal:

- Pencatatan: `http://pencatatan.localhost`
- Cetak laporan: `http://laporan.localhost`
- Notifikasi dan komunikasi: `http://notifikasi.localhost`
- Dashboard Traefik: `http://localhost:8080`

## Menjalankan tanpa Docker

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Lalu buka `http://127.0.0.1:8000/pencatatan`.
