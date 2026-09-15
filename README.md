# Sistem Reservasi & Pelaporan Fasilitas Kampus

Sistem web untuk pemesanan dan pelaporan fasilitas kampus UNDIP Tembalang.
Dibangun dengan Laravel 12, PHP 8.2, Tailwind CSS, dan SQLite (dev) / MySQL (produksi).

## Peran Pengguna (4 Aktor)

| Peran | Deskripsi |
|-------|-----------|
| **Pengunjung** | Tanpa akun. Browsing katalog fasilitas. |
| **Pengguna** | Mahasiswa/dosen/staf. Reservasi fasilitas, laporkan kerusakan. |
| **Petugas** | Kelola antrean reservasi & laporan kerusakan. |
| **Admin** | Kelola akun (verifikasi, buat akun Petugas), kelola data fasilitas. |

Status akun: `PENDING` → `ACTIVE` / `REJECTED`

## Fitur yang udah ada

- **Autentikasi**: register, login, logout dengan validasi peran & status akun
- **Katalog publik** (`/facilities`): browsing & detail fasilitas tanpa login
- **Pencarian & filter**: berdasarkan tipe, lokasi, kapasitas
- **CRUD fasilitas** (Admin): tambah, edit, nonaktifkan (soft delete dari status)
- **Verifikasi akun** (Admin): setujui/tolak akun pendaftar, buat akun Petugas/Pengguna
- **Middleware**: pembatasan akses berdasarkan peran (`role`) dan status (`active`)
- **Seeder data**

### Belum Diimplementasikan

- Reservasi fasilitas (FR-04 s.d. FR-07)
- Pelaporan kerusakan (FR-08, FR-09)
- Dashboard & operasional Petugas (FR-10 s.d. FR-15)
- Laporan rekapitulasi & ekspor (FR-20, FR-21)


## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka `http://localhost:8000`. Untuk MySQL, ubah `DB_*` di `.env` sebelum migrasi.

## Struktur Database

| Tabel | Kolom Utama | Status |
|-------|-------------|--------|
| `users` | name, email, role, status | Aktif |
| `facilities` | name, type, location, capacity, status | Aktif |
| `reservations` | user_id, facility_id, start/end_time, status | Struktur siap, fitur belum |
| `reports` | user_id, facility_id, category, photo, status | Struktur siap, fitur belum |


