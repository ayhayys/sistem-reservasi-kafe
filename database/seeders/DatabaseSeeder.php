<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Owner
        User::updateOrCreate(
            ['email' => 'owner@kafe-ramadhan.com'], // Cari berdasarkan email agar tidak duplikat
            [
                'name' => 'Owner',
                'phone' => '081234567890',
                'role' => 'owner',
                'password' => Hash::make('owner123'), // Fungsi Hash::make() ini yang mencegah error Bcrypt
            ]
        );

        // Membuat akun Kasir sekalian
        User::updateOrCreate(
            ['email' => 'cashier@kafe-ramadhan.com'],
            [
                'name' => 'Cashier',
                'phone' => '089876543210',
                'role' => 'cashier',
                'password' => Hash::make('cashier123'),
            ]
        );
        
        $this->command->info('Akun Owner dan Cashier berhasil dibuat dengan password yang di-hash!');
    }
}