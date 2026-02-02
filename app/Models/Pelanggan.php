<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pelanggan extends Authenticatable  
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pelanggans';
    
    protected $fillable = [
        'name_pelanggan',
        'email',
        'password',
        'tgl_lahir',
        'telepon',
        'alamat1',
        'alamat2',
        'alamat3',
        'kartu_id',
        'foto'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'password' => 'hashed',
    ];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_pelanggan');
    }

    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat1;
        if ($this->alamat2) {
            $alamat .= ', ' . $this->alamat2;
        }
        if ($this->alamat3) {
            $alamat .= ', ' . $this->alamat3;
        }
        return $alamat;
    }
}