<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'pakets';
    
    protected $fillable = [
        'name_paket',
        'jenis',
        'kategori',
        'jumlah_pax',
        'harga_paket',
        'deskripsi',
        'foto1',
        'foto2',
        'foto3'
    ];

    protected $casts = [
        'jumlah_pax' => 'integer',
        'harga_paket' => 'integer',
    ];

    protected $appends = ['foto1_url', 'foto2_url', 'foto3_url', 'harga_format', 'harga_per_pax'];

    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_paket');
    }

    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_paket, 0, ',', '.');
    }

    public function getHargaPerPaxAttribute()
    {
        return 'Rp ' . number_format($this->harga_paket, 0, ',', '.') . ' /pax';
    }

    public function getFoto1UrlAttribute()
    {
        if (!$this->foto1) {
            return asset('images/default-paket.jpg');
        }
        
        // Cek apakah file ada di public atau storage
        $publicPath = public_path('pakets/' . basename($this->foto1));
        if (file_exists($publicPath)) {
            // Jika file ada di public/pakets/
            return asset('pakets/' . basename($this->foto1));
        }
        
        // Coba akses dari storage
        if (Storage::disk('public')->exists($this->foto1)) {
            return Storage::url($this->foto1);
        }
        
        // Jika tidak ditemukan di kedua lokasi
        Log::warning('Foto paket tidak ditemukan: ' . $this->foto1);
        return asset('images/default-paket.jpg');
    }

    public function getFoto2UrlAttribute()
    {
        if (!$this->foto2) {
            return null;
        }
        
        $publicPath = public_path('pakets/' . basename($this->foto2));
        if (file_exists($publicPath)) {
            return asset('pakets/' . basename($this->foto2));
        }
        
        if (Storage::disk('public')->exists($this->foto2)) {
            return Storage::url($this->foto2);
        }
        
        Log::warning('Foto paket tidak ditemukan: ' . $this->foto2);
        return null;
    }

    public function getFoto3UrlAttribute()
    {
        if (!$this->foto3) {
            return null;
        }
        
        $publicPath = public_path('pakets/' . basename($this->foto3));
        if (file_exists($publicPath)) {
            return asset('pakets/' . basename($this->foto3));
        }
        
        if (Storage::disk('public')->exists($this->foto3)) {
            return Storage::url($this->foto3);
        }
        
        Log::warning('Foto paket tidak ditemukan: ' . $this->foto3);
        return null;
    }

    public function scopeByKategori($query, $kategori)
    {
        if ($kategori && $kategori != 'semua') {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeByJenis($query, $jenis)
    {
        if ($jenis && $jenis != 'semua') {
            return $query->where('jenis', $jenis);
        }
        return $query;
    }

    public static function getAllKategori()
    {
        return self::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');
    }

    public static function getAllJenis()
    {
        return self::select('jenis')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');
    }

    public function getDetailUrlAttribute()
    {
        return route('detail-paket', $this->id);
    }

    public function getPesanUrlAttribute()
    {
        return route('pesan', $this->id);
    }
}