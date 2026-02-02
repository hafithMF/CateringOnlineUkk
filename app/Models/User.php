<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function pengirimans()
    {
        return $this->hasMany(Pengiriman::class, 'id_user');
    }

    public function getRedirectRoute()
    {
        if ($this->isOwner()) {
            return '/owner/reports';
        } elseif ($this->isAdmin()) {
            return '/dashboard';
        } elseif ($this->isKurir()) {
            return '/kurir/dashboard';
        }
        
        return '/';
    }

    public function isAdmin()
    {
        return $this->level === 'admin';
    }

    public function isOwner()
    {
        return $this->level === 'owner';
    }

    public function isKurir()
    {
        return $this->level === 'kurir';
    }
}