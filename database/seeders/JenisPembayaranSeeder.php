<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembayaran;
use App\Models\DetailJenisPembayaran;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $jenisPembayarans = [
            [
                'metode_pembayaran' => 'Transfer Bank',
                'details' => [
                    [
                        'no_rek' => '1234567890',
                        'tempat_bayar' => 'BCA',
                        'logo' => 'bca.png'
                    ],
                    [
                        'no_rek' => '0987654321', 
                        'tempat_bayar' => 'Mandiri',
                        'logo' => 'mandiri.png'
                    ],
                    [
                        'no_rek' => '1122334455',
                        'tempat_bayar' => 'BNI',
                        'logo' => 'bni.png'
                    ]
                ]
            ],
            [
                'metode_pembayaran' => 'E-Wallet',
                'details' => [
                    [
                        'no_rek' => '081234567890',
                        'tempat_bayar' => 'OVO',
                        'logo' => 'ovo.png'
                    ],
                    [
                        'no_rek' => '081987654321',
                        'tempat_bayar' => 'GoPay',
                        'logo' => 'gopay.png'
                    ],
                    [
                        'no_rek' => '08111222333',
                        'tempat_bayar' => 'Dana',
                        'logo' => 'dana.png'
                    ]
                ]
            ],
            [
                'metode_pembayaran' => 'Tunai',
                'details' => []
            ],
            [
                'metode_pembayaran' => 'QRIS',
                'details' => [
                    [
                        'no_rek' => 'QR-CODE-CATERING',
                        'tempat_bayar' => 'QRIS',
                        'logo' => 'qris.png'
                    ]
                ]
            ]
        ];
        
        foreach ($jenisPembayarans as $jenis) {
            $jenisPembayaran = JenisPembayaran::create([
                'metode_pembayaran' => $jenis['metode_pembayaran']
            ]);
            
            foreach ($jenis['details'] as $detail) {
                DetailJenisPembayaran::create([
                    'id_jenis_pembayaran' => $jenisPembayaran->id,
                    'no_rek' => $detail['no_rek'],
                    'tempat_bayar' => $detail['tempat_bayar'],
                    'logo' => $detail['logo']
                ]);
            }
        }
    }
}