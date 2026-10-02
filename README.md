# Campus Facility System

Sistem peminjaman fasilitas dan pelaporan kerusakan kampus Universitas Diponegoro (Tembalang).
Dibangun dengan Laravel 12 + PHP 8.2. SQLite untuk pengembangan, MySQL untuk produksi (via `.env`).

## Peran

- **ADMIN** — kelola fasilitas (`/facilities/create`, `/facilities/{id}/edit`, nonaktifkan via `INACTIVE`) dan kelola/verifikasi akun (`/admin/users`).
- **USER** — ajukan dan batalkan reservasi miliknya (`/reservations`), buat dan lihat laporan kerusakannya (`/reports`).
- **STAFF** — setujui/tolak/batalkan reservasi (`/staff/reservations`), proses laporan kerusakan (`/staff/reports`).
- **Guest** — katalog dan detail fasilitas beserta slot ketersediaan (`/facilities`, `/facilities/{id}`); `INACTIVE` mengembalikan 404.

Akun baru mendaftar lewat `/register` sebagai `USER` berstatus `PENDING` dan baru bisa login setelah disetujui admin. Email wajib domain UNDIP (`students.undip.ac.id` / `undip.ac.id`).

## Rute utama

Sumber kebenaran: `routes/web.php`.

| Akses | Rute |
|---|---|
| Publik | `/` → redirect `/facilities`; `GET /facilities`, `GET /facilities/{id}` (+ slot `AVAILABLE`/`RESERVED`/`MAINTENANCE`) |
| Auth | `/register`, `/login`, `POST /logout`, `GET /dashboard` (cabang per role) |
| USER | `GET/POST /facilities/{facility}/reservations`, `GET /reservations`, `PATCH /reservations/{reservation}/cancel` |
| USER | `GET/POST /reports`, `GET /reports/create`, `GET /reports/{report}` (milik sendiri) |
| STAFF | `GET /staff/reservations`, `PATCH .../approve`, `.../reject`, `.../cancel` |
| STAFF | `GET /staff/reports`, `GET /staff/reports/{report}`, `PATCH /staff/reports/{report}` |
| ADMIN | `/facilities/create`, `/facilities/{id}/edit`, `DELETE` → `INACTIVE`; `/admin/users`, `/admin/users/create`, `PATCH /admin/users/{user}/verify`, `DELETE /admin/users/{user}` |

## Aturan bisnis penting

- Slot reservasi kelipatan 30 menit, jam operasional 07:00–20:00; reservasi hari yang sama minimal 2 jam dari sekarang.
- Jadwal bentrok (overlap dengan `PENDING`/`APPROVED`) ditolak; USER hanya bisa batalkan `PENDING` maksimal 2 jam sebelum mulai; STAFF hanya bisa batalkan `APPROVED` maksimal 30 menit sebelum mulai.
- Laporan `PROCESSING` mengubah fasilitas `AVAILABLE` → `MAINTENANCE`; `COMPLETED`/`REJECTED` wajib `resolution_note` dan mengembalikan `AVAILABLE` bila tidak ada laporan `PROCESSING` lain.
- Nonaktifkan fasilitas/akun = status `INACTIVE`, record tidak dihapus agar riwayat tetap terjaga.
- Rute sensitif dijaga middleware `auth` + `active` (hanya `ACTIVE`) + `role:...`.

## Cara menjalankan

```bash
composer install
copy .env.example .env   # Linux: cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka `http://localhost:8000`. Untuk MySQL, sesuaikan `DB_*` di `.env` sebelum migrasi.
Seeder mengisi 19 user (3 admin, 6 staff, 10 user, semua `ACTIVE`), 20 fasilitas, dan 5 reservasi contoh —
contoh: `dianberlian@undip.ac.id` (ADMIN), `marchell@undip.ac.id` (STAFF), `kayla@students.undip.ac.id` (USER); password lihat `database/seeders/DatabaseSeeder.php`.

## Test & CI

```bash
php artisan test
```

Mencakup RBAC, verifikasi akun, ketersediaan fasilitas, aturan waktu reservasi, batas cancel user/staff, dan alur laporan. Setiap push menjalankan `.github/workflows/ci.yml` (install, key, migrasi SQLite, test).
