<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimans';
    protected $fillable = [
        'id_pesan', 
        'id_user',      
        'status_kirim',
        'tgl_kirim',
        'tgl_tiba',
        'bukti_foto',
        'catatan',
    ];

    protected $casts = [
        'tgl_kirim' => 'datetime',
        'tgl_tiba' => 'datetime',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesan'); 
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getBuktiFotoUrlAttribute()
    {
        if (!$this->bukti_foto) {
            return null;
        }
        return Storage::url('pengiriman/' . $this->bukti_foto);
    }
    
    public function getBuktiFotoExistsAttribute()
    {
        if (!$this->bukti_foto) {
            return false;
        }
        return Storage::disk('public')->exists('pengiriman/' . $this->bukti_foto);
    }
}