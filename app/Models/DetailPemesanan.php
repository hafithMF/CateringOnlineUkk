<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanans';
    
    protected $fillable = [
        'id_pemesanan',
        'id_paket',
        'subtotal',  
    ];

    protected $casts = [
        'subtotal' => 'integer',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}