# Backend - Campus Facility System

Branch ini berisi pengembangan backend untuk **Campus Facility System**, yaitu sistem reservasi dan pelaporan fasilitas kampus.

Fokus utama backend adalah pengelolaan **database, autentikasi, otorisasi, akun pengguna, fasilitas, serta keamanan akses berdasarkan role** menggunakan Laravel.

## Scope Backend

Implementasi backend pada branch ini meliputi:

- setup database, migration, dan seeder,
- register, login, dan logout,
- verifikasi akun USER oleh Admin,
- pembatasan akses berdasarkan role,
- pengelolaan akun USER dan STAFF,
- pengelolaan data fasilitas,
- perlindungan route dari akses role yang tidak sesuai,
- integrasi backend dengan fitur reservasi dan laporan.

## Teknologi

- Laravel 12
- PHP 8.2+
- MySQL / MariaDB
- Eloquent ORM
- Composer

## Struktur Database

Tabel utama yang digunakan:

```text
users
facilities
reservations
reports
```

Relasi utama:

```text
User 1 --- N Reservations
User 1 --- N Reports

Facility 1 --- N Reservations
Facility 1 --- N Reports
```

## Autentikasi dan Role

Sistem memiliki tiga role utama yang dapat login:

### USER
- dapat melakukan registrasi mandiri,
- status awal akun adalah `PENDING`,
- hanya dapat login setelah disetujui Admin.

### STAFF
- dibuat langsung oleh Admin,
- menggunakan email domain `@undip.ac.id`,
- menangani reservasi dan laporan kerusakan.

### ADMIN
- mengelola akun USER dan STAFF,
- memverifikasi atau menolak registrasi USER,
- mengelola fasilitas,
- melihat rekap okupansi dan kerusakan.

## Status User

Status akun yang digunakan:

```text
PENDING
ACTIVE
REJECTED
INACTIVE
```

Hanya akun dengan status `ACTIVE` yang dapat mengakses fitur yang dilindungi.

## Middleware dan Keamanan Akses

Route penting dilindungi menggunakan middleware:

```text
auth
active
role
```

Contoh:

```php
Route::middleware(['auth', 'active', 'role:ADMIN'])->group(function () {
    // route khusus admin
});
```

Middleware digunakan untuk memastikan user hanya dapat mengakses fitur sesuai role dan status akunnya.

## Pengelolaan Akun

Admin dapat:

- membuat akun USER,
- membuat akun STAFF,
- menyetujui registrasi USER,
- menolak registrasi USER dengan alasan,
- menonaktifkan akun.

Akun STAFF hanya dapat dibuat menggunakan email dengan domain:

```text
@undip.ac.id
```

## Pengelolaan Fasilitas

Admin dapat:

- menambah fasilitas,
- mengubah data fasilitas,
- mengubah status fasilitas,
- menonaktifkan fasilitas.

Status fasilitas:

```text
AVAILABLE
MAINTENANCE
INACTIVE
```

Fasilitas berstatus `INACTIVE` tidak dapat digunakan untuk reservasi baru dan tidak ditampilkan kepada pengguna umum.

## Aturan Backend Reservasi

Backend menerapkan beberapa aturan utama:

- jam operasional reservasi `07.00–20.00`,
- interval waktu reservasi 30 menit,
- reservasi hari yang sama minimal 2 jam sebelum waktu mulai,
- jumlah peserta tidak boleh melebihi kapasitas fasilitas,
- jadwal yang bentrok dengan reservasi `PENDING` atau `APPROVED` ditolak,
- USER hanya dapat membatalkan reservasi `PENDING`,
- STAFF hanya dapat membatalkan reservasi `APPROVED` sebelum batas waktu tertentu.

## Laporan Kerusakan

Status laporan yang digunakan:

```text
NEW
PROCESSING
COMPLETED
REJECTED
```

Ketika laporan berubah menjadi `PROCESSING`, fasilitas terkait dapat berubah menjadi:

```text
MAINTENANCE
```

Jika penanganan selesai dan tidak ada laporan lain yang masih diproses, fasilitas dapat kembali menjadi:

```text
AVAILABLE
```

## Konfigurasi Database

Gunakan konfigurasi berikut pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_facility
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi MySQL/MariaDB masing-masing.

## Setup Project

Install dependency:

```bash
composer install
npm install
```

Salin file environment:

Windows:

```bash
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Buat symbolic link untuk storage:

```bash
php artisan storage:link
```

## Menjalankan Aplikasi

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Akses aplikasi melalui:

```text
http://localhost:8000
```

## Testing

Jalankan automated test dengan:

```bash
php artisan test
```

Testing mencakup:

- autentikasi,
- otorisasi dan role access,
- verifikasi akun,
- pengelolaan fasilitas,
- aturan reservasi,
- pembatalan reservasi,
- dan proses laporan kerusakan.

## Branch

```text
feature/person-1-backend
```

Branch ini digunakan untuk pengembangan bagian backend dan database sebelum perubahan digabungkan ke branch `main`.

## Kontributor

**Dian Berlian Hutasoit**  
Backend & Database Architect
