# Campus Facility System

Campus Facility System adalah aplikasi web untuk reservasi fasilitas dan pelaporan kerusakan fasilitas kampus Universitas Diponegoro.

Aplikasi dibangun menggunakan **Laravel 12**, **PHP 8.2**, dan **MySQL/MariaDB** sebagai basis data utama.

Fitur utama sistem meliputi:
- melihat katalog dan ketersediaan fasilitas,
- mengajukan dan membatalkan reservasi,
- memproses persetujuan reservasi,
- melaporkan kerusakan fasilitas,
- mengelola status perbaikan fasilitas,
- mengelola akun pengguna,
- serta melihat dan mengekspor rekap okupansi dan kerusakan.

---

## Teknologi

- Laravel 12
- PHP 8.2+
- MySQL / MariaDB
- Blade
- JavaScript
- Node.js & NPM
- Composer

---

## Role Pengguna

Sistem memiliki empat jenis akses.

### Guest

Pengunjung dapat:
- melihat daftar fasilitas,
- melihat detail fasilitas,
- melihat ketersediaan fasilitas per slot waktu,
- mencari fasilitas berdasarkan nama, tipe, lokasi, dan kapasitas.

Guest tidak perlu login.

### USER

USER dapat:
- mengajukan reservasi fasilitas,
- melihat riwayat dan status reservasi,
- membatalkan reservasi miliknya sendiri,
- membuat laporan kerusakan,
- melihat status laporan kerusakan.

Pengguna yang melakukan registrasi mandiri akan memiliki status `PENDING` dan hanya dapat login setelah disetujui oleh Admin.

### STAFF

STAFF dapat:
- melihat antrean reservasi,
- menyetujui reservasi,
- menolak reservasi,
- membatalkan reservasi yang sudah disetujui dalam kondisi mendesak,
- melihat laporan kerusakan,
- mengubah status laporan,
- memberikan catatan penyelesaian laporan.

### ADMIN

ADMIN dapat:
- menambah fasilitas,
- mengubah data fasilitas,
- menonaktifkan fasilitas,
- membuat akun USER dan STAFF,
- menyetujui atau menolak registrasi USER,
- menonaktifkan akun,
- melihat rekap okupansi dan kerusakan,
- mengekspor rekap dalam format CSV.

---

## Aturan Reservasi

Reservasi memiliki beberapa aturan utama:

- jam operasional: **07.00–20.00**,
- waktu reservasi menggunakan interval **30 menit**,
- reservasi pada hari yang sama harus dilakukan minimal **2 jam sebelum waktu penggunaan**,
- jumlah peserta tidak boleh melebihi kapasitas fasilitas,
- reservasi tidak dapat dibuat jika jadwal bertabrakan dengan reservasi berstatus `PENDING` atau `APPROVED`.

USER hanya dapat membatalkan reservasi berstatus `PENDING` paling lambat **2 jam sebelum waktu penggunaan**.

STAFF dapat membatalkan reservasi berstatus `APPROVED` paling lambat **30 menit sebelum waktu penggunaan** dan wajib memberikan alasan pembatalan.

---

## Alur Laporan Kerusakan

Status laporan terdiri dari:

- `NEW`
- `PROCESSING`
- `COMPLETED`
- `REJECTED`

Ketika laporan berubah menjadi `PROCESSING`, fasilitas terkait akan berubah menjadi `MAINTENANCE`.

Ketika laporan selesai atau ditolak dan tidak ada laporan lain yang masih diproses pada fasilitas tersebut, status fasilitas akan kembali menjadi `AVAILABLE`.

Status `COMPLETED` dan `REJECTED` wajib memiliki catatan penyelesaian.

---

## Struktur Status

### Status User

- `PENDING`
- `ACTIVE`
- `REJECTED`
- `INACTIVE`

### Status Fasilitas

- `AVAILABLE`
- `MAINTENANCE`
- `INACTIVE`

### Status Reservasi

- `PENDING`
- `APPROVED`
- `REJECTED`
- `CANCELLED`

---

## Rute Utama

### Public

```text
GET /
GET /facilities
GET /facilities/{facility}
GET /register
POST /register
GET /login
POST /login
```

### USER

```text
GET  /facilities/{facility}/reservations/create
POST /facilities/{facility}/reservations
GET  /reservations
PATCH /reservations/{reservation}/cancel

GET  /reports
GET  /reports/create
POST /reports
GET  /reports/{report}
```

### STAFF

```text
GET   /staff/reservations
PATCH /staff/reservations/{id}/approve
PATCH /staff/reservations/{id}/reject
PATCH /staff/reservations/{id}/cancel

GET   /staff/reports
GET   /staff/reports/{report}
PATCH /staff/reports/{report}
```

### ADMIN

```text
GET    /facilities/create
POST   /facilities
GET    /facilities/{facility}/edit
PUT    /facilities/{facility}
DELETE /facilities/{facility}

GET    /admin/users
GET    /admin/users/create
POST   /admin/users
PATCH  /admin/users/{user}/verify
DELETE /admin/users/{user}

GET    /admin/analytics
GET    /admin/analytics/export-csv
```

---

## Instalasi

### 1. Clone atau ekstrak source code

```bash
git clone https://github.com/dianberlianhutasoit/ProjectPPKKelompok.git
cd ProjectPPKKelompok
```

Jika menggunakan file ZIP, ekstrak terlebih dahulu lalu buka folder project melalui terminal.

---

### 2. Install dependency

```bash
composer install
npm install
```

---

### 3. Buat file environment

Windows:

```bash
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

---

### 4. Konfigurasi database

Buat database MySQL/MariaDB dengan nama:

```text
campus_facility
```

Kemudian sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_facility
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi MySQL pada perangkat masing-masing.

---

## Menyiapkan Database

Terdapat dua cara untuk menyiapkan database.

### Opsi 1 — Migration dan Seeder

Jalankan:

```bash
php artisan migrate:fresh --seed
```

Perintah tersebut akan membuat seluruh tabel dan mengisi data awal aplikasi.

Kemudian jalankan:

```bash
php artisan storage:link
```

---

### Opsi 2 — Import Database SQL

File database tersedia dalam:

```text
campus_facility.sql
```

Buat database `campus_facility` melalui phpMyAdmin atau MySQL, kemudian import file tersebut.

Setelah import selesai, pastikan konfigurasi database pada `.env` sudah sesuai.

---

## Menjalankan Aplikasi

Jalankan Laravel:

```bash
php artisan serve
```

Kemudian jalankan Vite pada terminal lain:

```bash
npm run dev
```

Buka aplikasi melalui:

```text
http://localhost:8000
```

---

## Akun Demo

Seeder menyediakan beberapa akun untuk pengujian.

### ADMIN

```text
Email    : dianberlian@undip.ac.id
Password : dian1234
```

### STAFF

```text
Email    : marchell@undip.ac.id
Password : marsel1234
```

### USER

```text
Email    : kayla@students.undip.ac.id
Password : kayla1234
```

Daftar akun lainnya dapat dilihat pada:

```text
database/seeders/DatabaseSeeder.php
```

---

## Email Registrasi

Registrasi hanya menerima email resmi UNDIP.

Domain yang diperbolehkan:

```text
@students.undip.ac.id
@undip.ac.id
```

Registrasi mandiri selalu menghasilkan akun dengan role `USER` dan status `PENDING`.

STAFF dibuat langsung oleh ADMIN dan harus menggunakan email `@undip.ac.id`.

---

## Penyimpanan Foto Laporan

Foto laporan kerusakan disimpan pada storage Laravel.

Pastikan menjalankan:

```bash
php artisan storage:link
```

agar file dapat diakses melalui folder `public/storage`.

---

## Rekap Okupansi dan Kerusakan

ADMIN dapat membuka:

```text
/admin/analytics
```

Halaman ini menampilkan:
- jumlah reservasi `APPROVED` pada setiap fasilitas,
- frekuensi laporan kerusakan,
- lokasi,
- kapasitas,
- status fasilitas,
- dan rasio kerusakan.

Data dapat diekspor ke file CSV melalui:

```text
/admin/analytics/export-csv
```

---

## Testing

Jalankan seluruh automated test dengan:

```bash
php artisan test
```

Pengujian mencakup:
- autentikasi dan otorisasi,
- Role-Based Access Control,
- verifikasi akun pengguna,
- pengelolaan fasilitas,
- ketersediaan fasilitas,
- aturan waktu reservasi,
- validasi jadwal bentrok,
- batas waktu pembatalan USER dan STAFF,
- serta proses laporan kerusakan.

---

## Struktur Database Utama

Tabel utama aplikasi:

```text
users
facilities
reservations
reports
```

Relasi utama:

```text
User
 ├── Reservations
 └── Reports

Facility
 ├── Reservations
 └── Reports
```

---

## Anggota Kelompok

- Dian Berlian Hutasoit
- Muhammad Firdaus Argifari
- Marchella Arkhina Ratunesia
- Kayla Febrina Laura Ayu

