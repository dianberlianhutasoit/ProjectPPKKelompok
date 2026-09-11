# Campus Facility System

Sistem Informasi Peminjaman dan Pelaporan Fasilitas Kampus.
Aplikasi ini dikembangkan menggunakan Laravel 12 dan PHP 8.2,
dengan SQLite sebagai basis data bawaan agar mudah dijalankan
di lingkungan pengembangan. Untuk kebutuhan produksi,
konfigurasi dapat dialihkan ke MySQL melalui berkas `.env`.

Sistem ini memiliki tiga peran pengguna, yaitu `ADMIN`, `STAFF`,
dan `USER`. Setiap akun memiliki status `PENDING` (baru mendaftar
dan belum dapat digunakan), `ACTIVE` (sudah terverifikasi dan
dapat digunakan), serta `REJECTED` (ditolak oleh admin).

## Peran Pengguna

- **Admin**: mengelola data fasilitas serta mengelola dan
  memverifikasi akun pengguna.
- **Staff dan User**: melihat dan mencari katalog fasilitas.
  (Fitur peminjaman dan pelaporan masih dalam tahap pengembangan.)

## Fitur yang Telah Diimplementasikan

### 1. Pendaftaran dan Masuk Pengguna

Pengunjung dapat mendaftarkan akun secara mandiri melalui
halaman `/register`. Akun yang baru dibuat otomatis berperan
sebagai `USER` dengan status `PENDING`, sehingga belum dapat
digunakan untuk masuk sebelum diverifikasi oleh admin.
Upaya masuk dengan akun `PENDING` atau `REJECTED` akan
dikembalikan ke halaman masuk disertai pesan yang sesuai.

Implementasi: `app/Http/Controllers/AuthController.php`.
Tampilan: `resources/views/auth/login.blade.php`,
`resources/views/auth/register.blade.php`.
Rute: bagian register, login, dan logout pada `routes/web.php`.

### 2. Dasbor Berdasarkan Peran

Setelah masuk, seluruh pengguna diarahkan ke `/dashboard`.
Dari titik ini sistem membagi tujuan berdasarkan peran:
admin diarahkan ke halaman pengelolaan akun,
sedangkan staff dan user diarahkan ke katalog fasilitas.
Dengan demikian terdapat satu pintu masuk yang bercabang
secara konsisten.

Implementasi: `app/Http/Controllers/DashboardController.php`.

### 3. Katalog Fasilitas untuk Umum

Halaman `/facilities` beserta halaman detail `/facilities/{id}`
dapat diakses tanpa masuk terlebih dahulu. Katalog dilengkapi
penyaringan berdasarkan tipe fasilitas, pencarian lokasi,
dan batas minimal kapasitas. Fasilitas berstatus `INACTIVE`
disembunyikan dari pengguna umum; apabila diakses langsung
melalui URL, sistem mengembalikan kode 404.

Implementasi: metode `index` dan `show` pada
`app/Http/Controllers/FacilityController.php`.
Tampilan: `resources/views/facilities/index.blade.php`,
`resources/views/facilities/show.blade.php`.

### 4. Pengelolaan Fasilitas oleh Admin

Selain melihat, admin dapat menambah fasilitas melalui
`/facilities/create` serta mengubahnya melalui
`/facilities/{id}/edit`. Penghapusan bersifat nonaktif:
data tidak dihapus dari basis data, melainkan statusnya
diubah menjadi `INACTIVE`. Pendekatan ini dipilih agar
riwayat peminjaman dan laporan yang merujuk pada fasilitas
tersebut tetap terjaga.

Implementasi: metode `create`, `store`, `edit`, `update`,
dan `destroy` pada `app/Http/Controllers/FacilityController.php`.
Formulir: `resources/views/facilities/create.blade.php`,
`resources/views/facilities/edit.blade.php`.
Pembatasan akses menggunakan middleware `role:ADMIN`
pada `routes/web.php`.

### 5. Pengelolaan dan Verifikasi Akun oleh Admin

Admin memiliki halaman khusus di `/admin/users` yang
menampilkan seluruh akun, dengan akun berstatus `PENDING`
ditampilkan paling atas untuk mempermudah verifikasi.
Dari halaman ini admin dapat menyetujui akun menjadi
`ACTIVE` atau menolaknya menjadi `REJECTED`.
Admin juga dapat membuatkan akun `STAFF` atau `USER`
secara manual, yang langsung berstatus aktif.

Implementasi: `app/Http/Controllers/Admin/UserController.php`.
Tampilan: `resources/views/admin/users/index.blade.php`,
`resources/views/admin/users/create.blade.php`.

### 6. Pengaman Halaman (Middleware)

Rute-rute penting dilindungi oleh dua middleware:

- `role` (`app/Http/Middleware/CheckRole.php`): memeriksa
  peran pengguna yang masuk, misalnya `role:ADMIN` akan
  menolak pengguna selain admin dengan kode 403.
- `active` (`app/Http/Middleware/EnsureUserActive.php`):
  memastikan hanya akun berstatus `ACTIVE` yang dapat
  melanjutkan; akun lain dikeluarkan dan dikembalikan
  ke halaman masuk.

Kedua middleware didaftarkan pada `bootstrap/app.php` dan digunakan pada `routes/web.php` dalam bentuk
`['auth', 'active', 'role:ADMIN']`.

## Struktur Backend

Daftar rute ada di `routes/web.php`. Ini tempat yang paling
disarankan dibaca pertama kali karena seluruh URL dikumpul di sana.

Logika tiap halaman ada di `app/Http/Controllers/`:
`AuthController.php` untuk pendaftaran, masuk, dan keluar;
`DashboardController.php` sebagai pembagi tujuan setelah masuk;
`FacilityController.php` untuk katalog publik sekaligus kelola fasilitas;
`Admin/UserController.php` untuk daftar, pembuatan, dan verifikasi akun.

Penjaga halaman ada di `app/Http/Middleware/`:
`CheckRole.php` untuk pembatasan peran dan
`EnsureUserActive.php` untuk pembatasan status akun.

Representasi tabel ada di `app/Models/`:
`User.php`, `Facility.php`, `Reservation.php`, dan `Report.php`.

Tampilan Blade ada di `resources/views/`:
`layouts/app.blade.php` sebagai kerangka dasar,
folder `auth/` untuk halaman masuk dan pendaftaran,
folder `facilities/` untuk daftar, detail, tambah, dan ubah fasilitas,
serta folder `admin/users/` untuk daftar dan tambah akun.

Bentuk tabel diatur lewat `database/migrations/`
(rinciannya ada di bagian bawah).
Data contoh untuk pengembangan diisi lewat
`database/seeders/DatabaseSeeder.php`.
Pendaftaran middleware dan koneksi basis data diatur di
`bootstrap/app.php`, `.env`, dan `config/database.php`.

Relasi antar model menggunakan Eloquent standar: satu pengguna
memiliki banyak peminjaman (`reservations`) dan banyak laporan
(`reports`); satu fasilitas juga memiliki banyak peminjaman
dan banyak laporan.

## Struktur Basis Data

- **Tabel `users`**: `id`, `name`, `email` (unik),
  `password` (tersimpan dalam bentuk hash),
  `role` (`ADMIN`/`STAFF`/`USER`),
  `status` (`PENDING`/`ACTIVE`/`REJECTED`).
  Dibuat pada `0001_01_01_000000_create_users_table.php`,
  kolom `status` ditambahkan pada
  `2026_09_07_000001_add_status_to_users_table.php`.
- **Tabel `facilities`**: `id`, `name`,
  `type` (contoh: Classroom, Computer Laboratory),
  `location`, `capacity` (bilangan bulat),
  `description` (dapat kosong),
  `status` (`AVAILABLE`/`MAINTENANCE`/`INACTIVE`).
  Dibuat pada `2026_09_05_083118_create_facilities_table.php`,
  nilai `INACTIVE` ditambahkan pada
  `2026_09_07_000002_add_inactive_to_facilities_table.php`.
- **Tabel `reservations`** (struktur tersedia, fitur belum):
  `user_id`, `facility_id`, `start_time`, `end_time`, `purpose`,
  `status` (`PENDING`/`APPROVED`/`REJECTED`/`CANCELLED`),
  `cancel_reason`.
  Didefinisikan pada `2026_09_05_085148_create_reservations_table.php`.
- **Tabel `reports`** (struktur tersedia, fitur belum):
  `user_id`, `facility_id`, `category`, `description`,
  `photo` (dapat kosong),
  `status` (`NEW`/`PROCESSING`/`COMPLETED`/`REJECTED`),
  `resolution_note`.
  Didefinisikan pada `2026_09_05_090227_create_reports_table.php`.

## Fitur yang Belum Diimplementasikan

Struktur tabel serta model `Reservation` dan `Report` telah
tersedia. Namun controller, rute, dan tampilan untuk alur
peminjaman fasilitas dan pelaporan kerusakan belum dibangun,
sehingga kedua alur tersebut belum dapat digunakan melalui web.

## Instalasi dan Cara Menjalankan

```bash
composer install
copy .env.example .env   # pada Linux: cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Kemudian buka `http://localhost:8000`.
Perintah `--seed` mengisi basis data dengan data contoh
agar halaman tidak kosong. Untuk menggunakan MySQL,
sesuaikan `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`,
dan `DB_PASSWORD` pada `.env` sebelum menjalankan migrasi.

## Alur Kerja Sistem

```
Pengunjung mendaftar di /register
  -> akun USER berstatus PENDING, menunggu verifikasi
Admin membuka /admin/users
  -> menyetujui (ACTIVE) atau menolak (REJECTED)
Pengguna yang sudah ACTIVE masuk dan diarahkan ke /dashboard
  -> admin menuju /admin/users (pengelolaan akun dan fasilitas)
  -> staff/user menuju /facilities (katalog fasilitas)
Pengunjung tanpa akun tetap dapat membuka
  /facilities dan halaman detailnya
```

## Integrasi Berkelanjutan (CI)

Setiap push ke GitHub menjalankan `.github/workflows/ci.yml`
secara otomatis: pemasangan dependensi, pembuatan kunci
aplikasi, migrasi basis data SQLite sementara, dan
`php artisan test`. Hasilnya dapat dilihat pada tab Actions.
