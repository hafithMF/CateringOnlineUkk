<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'level' => 'admin'
        ]);

        // Create owner
        User::create([
            'name' => 'Owner Catering',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456789'),
            'level' => 'owner'
        ]);

        // Create kurir
        User::create([
            'name' => 'Kurir Delivery',
            'email' => 'kurir@gmail.com',
            'password' => Hash::make('123456789'),
            'level' => 'kurir'
        ]);

        // Create sample pelanggan
        Pelanggan::create([
            'name_pelanggan' => 'John Doe',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('123456789'),
            'telepon' => '081234567',
            'alamat1' => 'Jl. Contoh No. 123, Jakarta',
            'tgl_lahir' => '1990-01-01'
        ]);

        // Create more sample pelanggan
        Pelanggan::factory(10)->create();
    }
}