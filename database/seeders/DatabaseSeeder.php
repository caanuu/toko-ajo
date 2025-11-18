<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Data Default (Role Owner)
        User::create([
            'name' => 'Owner Utama Toko Ajo',
            'email' => 'owner@tokoajo.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // TAMBAHAN BARU (Admin 1, Admin 2, Kasir)
        $users = [
            [
                'name' => 'Admin 1 (Gudang)',
                'email' => 'gudang@tokoajo.com',
                'password' => Hash::make('password123'),
                'role' => 'gudang',
            ],
            [
                'name' => 'Admin 2 (Pemasaran)',
                'email' => 'pemasaran@tokoajo.com',
                'password' => Hash::make('password123'),
                'role' => 'pemasaran',
            ],
            [
                'name' => 'Karyawan Kasir',
                'email' => 'kasir@tokoajo.com',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
