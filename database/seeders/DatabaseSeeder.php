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
        User::create([
            'name' => 'Dian Berlian',
            'email' => 'dianberlian@undip.ac.id',
            'password' => Hash::make('dian123'),
            'role' => 'ADMIN',
            'status' => 'ACTIVE'
        ]);


        User::create([
            'name' => 'Marchella Arkhina',
            'email' => 'marchell@undip.ac.id',
            'password' => Hash::make('marsel123'),
            'role' => 'STAFF',
            'status' => 'ACTIVE'
        ]);


        User::create([
            'name' => 'Kayla Febrina',
            'email' => 'kayla@students.undip.ac.id',
            'password' => Hash::make('kayla123'),
            'role' => 'USER',
            'status' => 'ACTIVE'
        ]);

        User::create([
            'name' => 'Firdaus Argifari',
            'email' => 'argifari@students.undip.ac.id',
            'password' => Hash::make('argi123'),
            'role' => 'USER',
            'status' => 'ACTIVE'
        ]);


        Facility::create([
            'name' => 'Ruang A301',
            'type' => 'Classroom',
            'location' => 'Gedung A Lantai 3',
            'capacity' => 40,
            'description' => 'Ruang kelas untuk kegiatan perkuliahan',
            'status' => 'AVAILABLE'
        ]);

        Facility::create([
            'name' => 'Ruang A302',
            'type' => 'Classroom',
            'location' => 'Gedung A Lantai 3',
            'capacity' => 40,
            'description' => 'Ruang kelas untuk kegiatan perkuliahan',
            'status' => 'AVAILABLE'
        ]);

        Facility::create([
            'name' => 'Ruang A303',
            'type' => 'Classroom',
            'location' => 'Gedung A Lantai 3',
            'capacity' => 40,
            'description' => 'Ruang kelas untuk kegiatan perkuliahan',
            'status' => 'AVAILABLE'
        ]);

        Facility::create([
            'name' => 'Ruang A305',
            'type' => 'Classroom',
            'location' => 'Gedung A Lantai 3',
            'capacity' => 40,
            'description' => 'Ruang kelas untuk kegiatan perkuliahan',
            'status' => 'AVAILABLE'
        ]);


        Facility::create([
            'name' => 'Lab B',
            'type' => 'Computer Laboratory',
            'location' => 'Gedung E Lantai 3',
            'capacity' => 35,
            'description' => 'Laboratorium komputer untuk praktikum mahasiswa',
            'status' => 'AVAILABLE'
        ]);


        Facility::create([
            'name' => 'Lab C',
            'type' => 'Computer Laboratory',
            'location' => 'Gedung E Lantai 3',
            'capacity' => 35,
            'description' => 'Laboratorium komputer untuk praktikum mahasiswa',
            'status' => 'AVAILABLE'
        ]);


        Facility::create([
            'name' => 'Lab D',
            'type' => 'Computer Laboratory',
            'location' => 'Gedung E Lantai 2',
            'capacity' => 35,
            'description' => 'Laboratorium komputer untuk praktikum mahasiswa',
            'status' => 'AVAILABLE'
        ]);

    }
}