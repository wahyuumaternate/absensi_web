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
                'password' => Hash::make('admin123'), // Jangan lupa untuk mengenkripsi password
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Another User',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Absensi::insert([
            [
                "id" => 3,
                "user_id" => 1,
                "tanggal" => "2024-11-04",
                "jam_masuk" => "08:12:06",
                "jam_keluar" => null,
                "status" => "hadir",
                "created_at" => now(), // menggunakan waktu saat ini
                "updated_at" => null, // atau bisa menggunakan now() jika diperlukan
            ]
        ]);
    }
}
