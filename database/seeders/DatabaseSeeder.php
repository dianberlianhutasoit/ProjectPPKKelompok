<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Dian Berlian', 'dianberlian@undip.ac.id', 'dian1231', 'ADMIN'],
            ['Bagus Prasetyo', 'bagus.prasetyo@undip.ac.id', 'bagus123', 'ADMIN'],
            ['Sinta Maharani', 'sinta.maharani@undip.ac.id', 'sinta123', 'ADMIN'],

            ['Marchella Arkhina', 'marchell@undip.ac.id', 'marsel123', 'STAFF'],
            ['Andi Kurniawan', 'andi.kurniawan@undip.ac.id', 'andi1231', 'STAFF'],
            ['Rina Wulandari', 'rina.wulandari@undip.ac.id', 'rina1231', 'STAFF'],
            ['Dedi Supriyanto', 'dedi.supriyanto@undip.ac.id', 'dedi1231', 'STAFF'],
            ['Nadia Putri', 'nadia.putri@undip.ac.id', 'nadia123', 'STAFF'],
            ['Hendra Gunawan', 'hendra.gunawan@undip.ac.id', 'hendra123', 'STAFF'],

            ['Kayla Febrina', 'kayla@students.undip.ac.id', 'kayla123', 'USER'],
            ['Firdaus Argifari', 'argifari@students.undip.ac.id', 'argi1231', 'USER'],
            ['Aulia Rahma', 'aulia.rahma@students.undip.ac.id', 'aulia123', 'USER'],
            ['Rizky Ramadhan', 'rizky.ramadhan@students.undip.ac.id', 'rizky123', 'USER'],
            ['Putri Anjani', 'putri.anjani@students.undip.ac.id', 'putri123', 'USER'],
            ['Bagas Pratama', 'bagas.pratama@students.undip.ac.id', 'bagas123', 'USER'],
            ['Intan Permata', 'intan.permata@students.undip.ac.id', 'intan123', 'USER'],
            ['Fajar Nugroho', 'fajar.nugroho@students.undip.ac.id', 'fajar123', 'USER'],
            ['Dinda Lestari', 'dinda.lestari@students.undip.ac.id', 'dinda123', 'USER'],
            ['Yoga Saputra', 'yoga.saputra@students.undip.ac.id', 'yoga1231', 'USER'],
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
            [
                'name' => 'Muladi Dome',
                'type' => 'Auditorium',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 4500,
                'description' => 'Gedung serbaguna UNDIP untuk kegiatan akademik, wisuda, seminar, pameran, dan acara berskala besar.',
                'image' => 'images/facilities/muladi-dome.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'ICT Center UNDIP',
                'type' => 'Computer Laboratory',
                'location' => 'Jl. Prof. Soedarto, Tembalang, Semarang',
                'capacity' => 100,
                'description' => 'Fasilitas teknologi informasi UNDIP yang mendukung kegiatan komputer, pelatihan, dan pembelajaran digital.',
                'image' => 'images/facilities/ict-center-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Gedung SA-MWA UNDIP',
                'type' => 'Meeting Room',
                'location' => 'Jl. Prof. Soedarto, Tembalang, Semarang',
                'capacity' => 100,
                'description' => 'Gedung Senat Akademik dan Majelis Wali Amanat UNDIP yang memiliki ruang untuk rapat dan kegiatan kelembagaan.',
                'image' => 'images/facilities/sa-mwa-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Dome FEB UNDIP',
                'type' => 'Hall',
                'location' => 'Fakultas Ekonomika dan Bisnis UNDIP, Tembalang',
                'capacity' => 500,
                'description' => 'Ruang kegiatan FEB UNDIP untuk seminar, kegiatan mahasiswa, dan acara fakultas.',
                'image' => 'images/facilities/dome-feb-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Ruang Lantai 6 Gedung Acintya Prasada FSM',
                'type' => 'Seminar Room',
                'location' => 'Fakultas Sains dan Matematika UNDIP, Tembalang',
                'capacity' => 150,
                'description' => 'Ruang kegiatan di Gedung Acintya Prasada FSM untuk seminar, pertemuan, dan kegiatan akademik.',
                'image' => 'images/facilities/acintya-prasada-lantai-6.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Student Center UNDIP',
                'type' => 'Student Activity Center',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 300,
                'description' => 'Pusat kegiatan mahasiswa UNDIP untuk kegiatan organisasi, pertemuan, dan aktivitas kemahasiswaan.',
                'image' => 'images/facilities/student-center-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'GOR Basket UNDIP',
                'type' => 'Sports',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 500,
                'description' => 'Gedung olahraga UNDIP untuk kegiatan basket dan aktivitas olahraga lainnya.',
                'image' => 'images/facilities/gor-basket-undip.png',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Stadion UNDIP',
                'type' => 'Sports',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 1000,
                'description' => 'Stadion kampus untuk sepak bola, atletik, latihan, dan kegiatan olahraga.',
                'image' => 'images/facilities/stadion-undip.png',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Gedung Bulutangkis UNDIP',
                'type' => 'Sports',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 300,
                'description' => 'Gedung olahraga untuk latihan dan kegiatan bulutangkis sivitas akademika UNDIP.',
                'image' => 'images/facilities/gedung-bulutangkis-undip.png',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Auditorium Prof. Soedarto',
                'type' => 'Auditorium',
                'location' => 'Jl. Prof. Soedarto, Tembalang, Semarang',
                'capacity' => 1500,
                'description' => 'Auditorium UNDIP untuk wisuda, seminar, rapat besar, dan berbagai kegiatan universitas.',
                'image' => 'images/facilities/auditorium-prof-soedarto.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'UPT Perpustakaan UNDIP',
                'type' => 'Library',
                'location' => 'Gedung Widya Puraya, Kampus UNDIP Tembalang',
                'capacity' => 400,
                'description' => 'Perpustakaan pusat UNDIP untuk membaca, belajar, diskusi, dan mengakses koleksi akademik.',
                'image' => 'images/facilities/perpustakaan-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Masjid Kampus UNDIP',
                'type' => 'Prayer Room',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 1000,
                'description' => 'Masjid kampus UNDIP untuk ibadah dan kegiatan keagamaan sivitas akademika.',
                'image' => 'images/facilities/masjid-kampus-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Gedung Laboratorium Terpadu UNDIP',
                'type' => 'Laboratory',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 100,
                'description' => 'Fasilitas laboratorium terpadu UNDIP untuk penelitian, praktikum, dan kegiatan akademik.',
                'image' => 'images/facilities/laboratorium-terpadu-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Jogging Track UNDIP',
                'type' => 'Sports',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 100,
                'description' => 'Area olahraga terbuka UNDIP untuk jogging dan kegiatan kebugaran.',
                'image' => 'images/facilities/jogging-track-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Lapangan Multifungsi FEB UNDIP',
                'type' => 'Sports',
                'location' => 'Fakultas Ekonomika dan Bisnis UNDIP, Tembalang',
                'capacity' => 100,
                'description' => 'Lapangan olahraga FEB UNDIP untuk basket, voli, futsal, dan kegiatan mahasiswa.',
                'image' => 'images/facilities/lapangan-multifungsi-feb.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Training Center UNDIP',
                'type' => 'Training Center',
                'location' => 'Kampus UNDIP Tembalang, Semarang',
                'capacity' => 150,
                'description' => 'Fasilitas UNDIP untuk kegiatan pelatihan, workshop, pertemuan, dan pengembangan kapasitas sivitas akademika.',
                'image' => 'images/facilities/training-center-undip.png',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Lapangan Futsal FEB UNDIP',
                'type' => 'Sports',
                'location' => 'Fakultas Ekonomika dan Bisnis UNDIP, Tembalang',
                'capacity' => 100,
                'description' => 'Lapangan futsal outdoor FEB UNDIP untuk olahraga dan kegiatan mahasiswa.',
                'image' => 'images/facilities/lapangan-futsal-feb.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Aula Dekanat Lama FSM UNDIP',
                'type' => 'Hall',
                'location' => 'Fakultas Sains dan Matematika UNDIP, Tembalang',
                'capacity' => 200,
                'description' => 'Aula FSM untuk kegiatan akademik, kemahasiswaan, sosialisasi, dan pertemuan.',
                'image' => 'images/facilities/aula-dekanat-lama-fsm.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Ruang Seminar Fakultas Hukum UNDIP',
                'type' => 'Seminar Room',
                'location' => 'Fakultas Hukum UNDIP, Tembalang',
                'capacity' => 150,
                'description' => 'Ruang seminar untuk seminar, diskusi akademik, sidang, dan kegiatan fakultas.',
                'image' => 'images/facilities/ruang-seminar-fh-undip.jpg',
                'status' => 'AVAILABLE',
            ],
            [
                'name' => 'Hall Gedung C FEB UNDIP',
                'type' => 'Hall',
                'location' => 'Fakultas Ekonomika dan Bisnis UNDIP, Tembalang',
                'capacity' => 170,
                'description' => 'Ruang serbaguna di Gedung C FEB UNDIP untuk seminar, pertemuan, dan kegiatan akademik maupun kemahasiswaan.',
                'image' => 'images/facilities/hall-gedung-c-feb.jpg',
                'status' => 'AVAILABLE',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        Reservation::create([
            'user_id' => 10, 'facility_id' => 1,
            'participants' => 25,
            'start_time' => Carbon::parse('2026-09-20 08:00'),
            'end_time' => Carbon::parse('2026-09-20 09:00'),
            'purpose' => 'Rapat UKM Informatika',
            'status' => 'APPROVED',
        ]);
        Reservation::create([
            'user_id' => 11, 'facility_id' => 1,
            'participants' => 10,
            'start_time' => Carbon::parse('2026-09-20 08:30'),
            'end_time' => Carbon::parse('2026-09-20 09:30'),
            'purpose' => 'Diskusi kelompok (tes overlap)',
            'status' => 'PENDING',
        ]);
        Reservation::create([
            'user_id' => 12, 'facility_id' => 5,
            'participants' => 30,
            'start_time' => Carbon::parse('2026-09-21 13:00'),
            'end_time' => Carbon::parse('2026-09-21 14:30'),
            'purpose' => 'Praktikum pengganti',
            'status' => 'PENDING',
        ]);
        Reservation::create([
            'user_id' => 13, 'facility_id' => 2,
            'participants' => 20,
            'start_time' => Carbon::parse('2026-09-18 10:00'),
            'end_time' => Carbon::parse('2026-09-18 11:00'),
            'purpose' => 'Dibatalkan karena hujan',
            'status' => 'CANCELLED',
        ]);
        Reservation::create([
            'user_id' => 14, 'facility_id' => 3,
            'participants' => 15,
            'start_time' => Carbon::parse('2026-09-19 09:00'),
            'end_time' => Carbon::parse('2026-09-19 10:00'),
            'purpose' => 'Ditolak karena maintenance',
            'status' => 'REJECTED',
            'cancel_reason' => 'Ruangan dalam perbaikan',
        ]);
    }
}
