<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
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
            // ADMIN 
            ['Dian Berlian', 'dianberlian@undip.ac.id', 'dian123', 'ADMIN'],
            ['Bagus Prasetyo', 'bagus.prasetyo@undip.ac.id', 'bagus123', 'ADMIN'],
            ['Sinta Maharani', 'sinta.maharani@undip.ac.id', 'sinta123', 'ADMIN'],

            // STAFF 
            ['Marchella Arkhina', 'marchell@undip.ac.id', 'marsel123', 'STAFF'],
            ['Andi Kurniawan', 'andi.kurniawan@undip.ac.id', 'andi123', 'STAFF'],
            ['Rina Wulandari', 'rina.wulandari@undip.ac.id', 'rina123', 'STAFF'],
            ['Dedi Supriyanto', 'dedi.supriyanto@undip.ac.id', 'dedi123', 'STAFF'],
            ['Nadia Putri', 'nadia.putri@undip.ac.id', 'nadia123', 'STAFF'],
            ['Hendra Gunawan', 'hendra.gunawan@undip.ac.id', 'hendra123', 'STAFF'],

            // USER 
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
            ['Ruang Kuliah A101', 'Classroom', 'Jl. Prof. Jacub Rais, Tembalang', 60, 'Gedung A Lt.1 FSM. Ruang kuliah untuk perkuliahan.'],
            ['Ruang Kuliah A201', 'Classroom', 'Jl. Prof. Jacub Rais, Tembalang', 50, 'Gedung A Lt.2 FSM. Ruang kuliah untuk perkuliahan.'],
            ['Ruang Kuliah B101', 'Classroom', 'Jl. Prof. Jacub Rais, Tembalang', 45, 'Gedung B Lt.1 FSM. Ruang kuliah untuk perkuliahan.'],
            ['Lab Informatika B', 'Computer Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 35, 'Gedung E Lt.2 FSM. Lab Komputer Informatika.'],
            ['Lab Informatika C', 'Computer Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 35, 'Gedung E Lt.2 FSM. Lab Komputer Informatika.'],
            ['Lab Komputasi Matematika A', 'Computer Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 30, 'Gedung F Lt.2 FSM. Lab Komputer Matematika.'],
            ['Lab Statistika B', 'Computer Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 30, 'Gedung B Lt.3 FSM. Lab Komputer Statistika.'],
            ['Lab Acintya Prasada 501', 'Computer Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 40, 'Gedung Acintya Prasada Lt.5 FSM. Lab komputer FSM, biasanya dipakai untuk UTBK.'],
            ['Lab Kimia A', 'Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 25, 'Gedung C Lt.1 FSM. Lab Praktikum Dasar Kimia.'],
            ['Lab Biologi B', 'Laboratory', 'Jl. Prof. Jacub Rais, Tembalang', 25, 'Gedung C Lt.2 FSM. Lab Praktikum Biologi.'],
            ['Ruang Seminar Acintya Prasada', 'Seminar Room', 'Jl. Prof. Jacub Rais, Tembalang', 100, 'Gedung Acintya Prasada Lt.4 FSM. Ruangan untuk seminar, sidang skripsi/tesis, dan kuliah tamu.'],
            ['Perpustakaan FSM', 'Library', 'Jl. Prof. Jacub Rais, Tembalang', 80, 'Gedung B Lt.2 FSM. Tempat kumpulan buku, sekaligus bisa menjadi co-working space.'],

            ['Auditorium Prof. Soedarto', 'Auditorium', 'Jl. Prof. Soedharto, Tembalang', 800, 'Gedung serbaguna utama UNDIP untuk wisuda dan acara besar.'],
            ['Student Center Widya Puraya', 'Hall', 'Jl. Prof. Soedharto, Tembalang', 300, 'Pusat kegiatan mahasiswa dan sekretariat UKM.'],
            ['Lab Komputer ICT Center (Elearning A)', 'Computer Laboratory', 'Jl. Prof. Soedharto, Tembalang', 50, 'Gedung ICT Center Lt.3. Lab komputer pusat untuk e-learning dan UTBK.'],
            ['Stadion UNDIP', 'Sports', 'Jl. Prof. Soedharto, Tembalang', 500, 'Lapangan sepak bola dan atletik untuk latihan dan lomba.'],
            ['Masjid Kampus UNDIP', 'Prayer Room', 'Jl. Prof. Soedharto, Tembalang', 1000, 'Masjid utama kampus, bisa dipakai kajian dan tarawih.'],
            ['UPT Perpustakaan Undip', 'Library', 'Jl. Prof. Soedharto, Tembalang', 400, 'Perpustakaan Universitas, tempat kumpulan buku, dan co-working space.'],
            ['Ruang Rapat Widya Puraya', 'Meeting Room', 'Jl. Prof. Soedharto, Tembalang', 20, 'Gedung Widya Puraya Lt.2. Rapat pimpinan dan tamu resmi universitas.', 'MAINTENANCE'],
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

        Reservation::create([
            'user_id' => 10, 'facility_id' => 1,
            'start_time' => Carbon::parse('2026-09-20 08:00'),
            'participants' =>20,
            'end_time' => Carbon::parse('2026-09-20 09:00'),
            'purpose' => 'Rapat UKM Informatika',
            'status' => 'APPROVED',
        ]);
        Reservation::create([
            'user_id' => 11, 'facility_id' => 1,
            'start_time' => Carbon::parse('2026-09-20 08:30'),
            'participants' =>20,
            'end_time' => Carbon::parse('2026-09-20 09:30'),
            'purpose' => 'Diskusi kelompok (tes overlap)',
            'status' => 'PENDING',
        ]);
        Reservation::create([
            'user_id' => 12, 'facility_id' => 5,
            'start_time' => Carbon::parse('2026-09-21 13:00'),
            'participants' =>20,
            'end_time' => Carbon::parse('2026-09-21 14:30'),
            'purpose' => 'Praktikum pengganti',
            'status' => 'PENDING',
        ]);
        Reservation::create([
            'user_id' => 13, 'facility_id' => 2,
            'start_time' => Carbon::parse('2026-09-18 10:00'),
            'participants' =>20,
            'end_time' => Carbon::parse('2026-09-18 11:00'),
            'purpose' => 'Dibatalkan karena hujan',
            'status' => 'CANCELLED',
        ]);
        Reservation::create([
            'user_id' => 14, 'facility_id' => 3,
            'start_time' => Carbon::parse('2026-09-19 09:00'),
            'participants' =>20,
            'end_time' => Carbon::parse('2026-09-19 10:00'),
            'purpose' => 'Ditolak karena maintenance',
            'status' => 'REJECTED',
            'cancel_reason' => 'Ruangan dalam perbaikan',
        ]);
    }
}