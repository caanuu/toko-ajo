<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuai Skenario Pengujian Tabel 4.1 No 1 (Login dengan data valid)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@tokoajo.com',
            'password' => Hash::make('password123'), // Password default
            'role' => 'owner', // Sesuai struktur organisasi
        ]);
    }
}
