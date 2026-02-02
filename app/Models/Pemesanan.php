<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    
    protected $fillable = [
        'id_pelanggan',
        'id_jenis_bayar',
        'no_resi',
        'tgl_pesan',
        'status_pesan',
        'total_bayar',
    ];

    protected $casts = [
        'tgl_pesan' => 'datetime',
        'total_bayar' => 'integer',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_bayar');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pesan');
    }

    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'Menunggu Konfirmasi' => 'warning',
            'Menunggu Kurir' => 'primary',
            'Selesai' => 'success',
            'Dibatalkan' => 'danger'
        ];
        
        return $statuses[$this->status_pesan] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        return ucwords(strtolower($this->status_pesan));
    }
}