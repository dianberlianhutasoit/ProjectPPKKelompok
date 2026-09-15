<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            // ADMIN (pengelola sarpras / akademik)
            ['Dian Berlian', 'dianberlian@undip.ac.id', 'dian123', 'ADMIN'],
            ['Bagus Prasetyo', 'bagus.prasetyo@undip.ac.id', 'bagus123', 'ADMIN'],
            ['Sinta Maharani', 'sinta.maharani@undip.ac.id', 'sinta123', 'ADMIN'],

            // STAFF (laboran, pustakawan, operator gedung)
            ['Marchella Arkhina', 'marchell@undip.ac.id', 'marsel123', 'STAFF'],
            ['Andi Kurniawan', 'andi.kurniawan@undip.ac.id', 'andi123', 'STAFF'],
            ['Rina Wulandari', 'rina.wulandari@undip.ac.id', 'rina123', 'STAFF'],
            ['Dedi Supriyanto', 'dedi.supriyanto@undip.ac.id', 'dedi123', 'STAFF'],
            ['Nadia Putri', 'nadia.putri@undip.ac.id', 'nadia123', 'STAFF'],
            ['Hendra Gunawan', 'hendra.gunawan@undip.ac.id', 'hendra123', 'STAFF'],

            // USER (mahasiswa)
            ['Kayla Febrina', 'kayla@students.undip.ac.id', 'kayla123', 'USER'],
            ['Firdaus Argifari', 'argifari@students.undip.ac.id', 'argi123', 'USER'],
            ['Aulia Rahma', 'aulia.rahma@students.undip.ac.id', 'aulia123', 'USER'],
            ['Rizky Ramadhan', 'rizky.ramadhan@students.undip.ac.id', 'rizky123', 'USER'],
            ['Putri Anjani', 'putri.anjani@students.undip.ac.id', 'putri123', 'USER'],
            ['Bagas Pratama', 'bagas.pratama@students.undip.ac.id', 'bagas123', 'USER'],
            ['Intan Permata', 'intan.permata@students.undip.ac.id', 'intan123', 'USER'],
            ['Fajar Nugroho', 'fajar.nugroho@students.undip.ac.id', 'fajar123', 'USER'],
            ['Dinda Lestari', 'dinda.lestari@students.undip.ac.id', 'dinda123', 'USER'],
            ['Yoga Saputra', 'yoga.saputra@students.undip.ac.id', 'yoga123', 'USER'],
        ];

        foreach ($users as [$name, $email, $plain, $role]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($plain),
                'role' => $role,
                'status' => 'ACTIVE',
            ]);
        }

        $facilities = [
            // --- FSM Tembalang ---
            ['Ruang Kuliah A101', 'Classroom', 'Gedung A Lt. 1, FSM, Kampus Tembalang', 60, 'Ruang kuliah reguler untuk perkuliahan umum FSM.'],
            ['Ruang Kuliah A201', 'Classroom', 'Gedung A Lt. 2, FSM, Kampus Tembalang', 50, 'Ruang kuliah reguler kapasitas menengah.'],
            ['Ruang Kuliah B101', 'Classroom', 'Gedung B Lt. 1, FSM, Kampus Tembalang', 45, 'Ruang kuliah dekat Lab Statistika.'],
            ['Lab Informatika B', 'Computer Laboratory', 'Gedung E Lt. 2, FSM, Kampus Tembalang', 35, 'Praktikum pemrograman dan basis data Informatika.'],
            ['Lab Informatika C', 'Computer Laboratory', 'Gedung E Lt. 2, FSM, Kampus Tembalang', 35, 'Praktikum pemrograman dan jaringan Informatika.'],
            ['Lab Komputasi Matematika A', 'Computer Laboratory', 'Gedung F Lt. 2, FSM, Kampus Tembalang', 30, 'Praktikum komputasi dan pengolahan data Matematika.'],
            ['Lab Statistika B', 'Computer Laboratory', 'Gedung B Lt. 3, FSM, Kampus Tembalang', 30, 'Praktikum statistika dan analisis data.'],
            ['Lab Acintya Prasada 501', 'Computer Laboratory', 'Gedung Acintya Prasada Lt. 5, FSM, Kampus Tembalang', 40, 'Lab komputer serbaguna, juga dipakai UTBK.'],
            ['Lab Kimia Dasar', 'Laboratory', 'Gedung C Lt. 1, FSM, Kampus Tembalang', 25, 'Praktikum kimia dasar Kimia dan Biologi.'],
            ['Lab Biologi', 'Laboratory', 'Gedung C Lt. 2, FSM, Kampus Tembalang', 25, 'Praktikum biologi dan mikrobiologi.'],
            ['Ruang Seminar Acintya Prasada', 'Seminar Room', 'Gedung Acintya Prasada Lt. 4, FSM, Kampus Tembalang', 100, 'Seminar, sidang skripsi/tesis, dan kuliah tamu.'],
            ['Perpustakaan FSM', 'Library', 'Gedung B Lt. 2, FSM, Kampus Tembalang', 80, 'Ruang baca dan koleksi FSM, ada co-working space.'],

            // --- Venue umum UNDIP Tembalang ---
            ['Auditorium Prof. Soedarto', 'Auditorium', 'Jl. Prof. Soedarto, Kampus Tembalang', 800, 'Gedung serbaguna utama UNDIP untuk wisuda dan acara besar.'],
            ['Student Center Widya Puraya', 'Hall', 'Jl. Prof. Soedharto, Kampus Tembalang', 300, 'Pusat kegiatan mahasiswa dan sekretariat UKM.'],
            ['Lab Komputer ICT Center (Elearning A)', 'Computer Laboratory', 'Gedung ICT Center Lt. 3, Kampus Tembalang', 50, 'Lab komputer pusat untuk e-learning dan UTBK.'],
            ['Stadion UNDIP', 'Sports', 'Kampus Tembalang', 500, 'Lapangan sepak bola dan atletik untuk latihan dan lomba.'],
            ['Graha Sabha Pratama', 'Hall', 'Kampus Tembalang', 1000, 'Gedung pertemuan besar untuk konser, expo, dan resepsi.'],
            ['Masjid Kampus UNDIP', 'Prayer Room', 'Kampus Tembalang', 1000, 'Masjid utama kampus, bisa dipakai kajian dan tarawih.'],
            ['Perpustakaan Pusat UNDIP', 'Library', 'Kampus Tembalang', 400, 'Perpustakaan pusat dengan ruang baca dan diskusi.'],
            ['Ruang Rapat Pimpinan Widya Puraya', 'Meeting Room', 'Gedung Widya Puraya Lt. 2, Kampus Tembalang', 20, 'Rapat pimpinan dan tamu resmi universitas.', 'MAINTENANCE'],
        ];

        foreach ($facilities as $f) {
            Facility::create([
                'name' => $f[0],
                'type' => $f[1],
                'location' => $f[2],
                'capacity' => $f[3],
                'description' => $f[4],
                'status' => $f[5] ?? 'AVAILABLE',
            ]);
        }
    }
}
