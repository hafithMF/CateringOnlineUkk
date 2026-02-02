<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\JenisPembayaran;
use App\Models\DetailJenisPembayaran;
use App\Models\Paket;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@catering.com',
            'password' => Hash::make('password123'),
            'level' => 'admin',
        ]);

        // Create owner user
        User::create([
            'name' => 'Owner',
            'email' => 'owner@catering.com',
            'password' => Hash::make('password123'),
            'level' => 'owner',
        ]);

        // Create kurir user
        User::create([
            'name' => 'Kurir 1',
            'email' => 'kurir@catering.com',
            'password' => Hash::make('password123'),
            'level' => 'kurir',
        ]);

        // Create pelanggan
        Pelanggan::create([
            'name_pelanggan' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'telepon' => '081234567890',
            'alamat1' => 'Jl. Contoh No. 123',
            'alamat2' => 'Kel. Contoh',
            'alamat3' => 'Kota Contoh',
        ]);

        // Jenis Pembayaran
        $transfer = JenisPembayaran::create([
            'metode_pembayaran' => 'Transfer Bank',
        ]);

        $cod = JenisPembayaran::create([
            'metode_pembayaran' => 'Cash on Delivery',
        ]);

        // Detail Jenis Pembayaran
        DetailJenisPembayaran::create([
            'id_jenis_pembayaran' => $transfer->id,
            'no_rek' => '1234567890',
            'tempal_bayar' => 'Bank BCA',
            'logo' => 'bca.png',
        ]);

        // Paket Catering
        $pakets = [
            [
                'name_paket' => 'Paket Pernikahan Premium',
                'jenis' => 'Prasmanan',
                'kategori' => 'Pernikahan',
                'jumlah_pax' => 100,
                'harga_paket' => 5000000,
                'deskripsi' => 'Paket premium untuk pernikahan dengan menu lengkap',
            ],
            [
                'name_paket' => 'Paket Ulang Tahun Anak',
                'jenis' => 'Box',
                'kategori' => 'Ulang Tahun',
                'jumlah_pax' => 50,
                'harga_paket' => 2500000,
                'deskripsi' => 'Paket khusus ulang tahun anak dengan tema menarik',
            ],
            [
                'name_paket' => 'Paket Rapat Eksekutif',
                'jenis' => 'Box',
                'kategori' => 'Rapat',
                'jumlah_pax' => 20,
                'harga_paket' => 1500000,
                'deskripsi' => 'Paket rapat dengan menu sehat dan praktis',
            ],
        ];

        foreach ($pakets as $paket) {
            Paket::create($paket);
        }

        $this->call([
            // Panggil seeder lainnya jika ada
        ]);
    }
}