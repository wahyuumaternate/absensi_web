<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'jabatan' => 'ADMIN',
                'foto' => 'https://wahyuumaternate.my.id/foto1.png',
                'password' => Hash::make('admin123'), // Jangan lupa untuk mengenkripsi password
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WAHYU UMATERNATE',
                'email' => 'wahyu@gmail.com',
                'jabatan' => 'KABID P3RT',
                'foto' => 'https://wahyuumaternate.my.id/foto1.png',
                'password' => Hash::make('wahyu123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Riskal',
                'email' => 'riskal@gmail.com',
                'jabatan' => 'SEKUM',
                'foto' => 'https://psikologi.unair.ac.id/wp-content/uploads/2017/03/foto_profil-sanny.png',
                'password' => Hash::make('riskal123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Absensi::insert([
            [
                "id" => 1,
                "user_id" => 1,
                "tanggal" => "2024-11-04",
                "jam_masuk" => now()->format('H:i:s'),
                "jam_keluar" => null,
                "status" => "hadir",
                "created_at" => now(), // menggunakan waktu saat ini
                "updated_at" => null, // atau bisa menggunakan now() jika diperlukan
            ]
        ]);
        Absensi::insert([
            [
                "id" => 2,
                "user_id" => 1,
                "tanggal" => "2024-11-04",
                "jam_masuk" => now()->format('H:i:s'),
                "jam_keluar" => null,
                "status" => "terlambat",
                "created_at" => now(), // menggunakan waktu saat ini
                "updated_at" => null, // atau bisa menggunakan now() jika diperlukan
            ]
        ]);
        Absensi::insert([
            [
                "id" => 3,
                "user_id" => 1,
                "tanggal" => "2024-11-04",
                "jam_masuk" => now()->format('H:i:s'),
                "jam_keluar" => null,
                "status" => "lembur",
                "created_at" => now(), // menggunakan waktu saat ini
                "updated_at" => null, // atau bisa menggunakan now() jika diperlukan
            ]
        ]);
        Absensi::insert([
            [
                "id" => 4,
                "user_id" => 1,
                "tanggal" => "2024-11-04",
                "jam_masuk" => now()->format('H:i:s'),
                "jam_keluar" => null,
                "status" => "tidak masuk",
                "created_at" => now(), // menggunakan waktu saat ini
                "updated_at" => null, // atau bisa menggunakan now() jika diperlukan
            ]
        ]);
    }
}
