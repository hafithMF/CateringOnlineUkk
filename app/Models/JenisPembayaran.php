<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pembayarans';
    
    protected $fillable = [
        'metode_pembayaran'
    ];

    public function detailJenisPembayarans()
    {
        return $this->hasMany(DetailJenisPembayaran::class, 'id_jenis_pembayaran');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_jenis_bayar');
    }
}