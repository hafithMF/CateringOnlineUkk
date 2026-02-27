<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DetailJenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'detail_jenis_pembayarans';
    
    protected $fillable = [
        'id_jenis_pembayaran',
        'no_rek',
        'tempat_bayar',
        'logo'
    ];

    protected $appends = ['logo_url'];

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran');
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        $publicPath = 'jenis-pembayaran/' . $this->logo;
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }

        $storagePath = 'public/jenis-pembayaran/' . $this->logo;
        if (Storage::exists($storagePath)) {
            return Storage::url($storagePath);
        }

        return null;
    }
}