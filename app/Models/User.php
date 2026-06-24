<?php

namespace App\Models;

// PERBAIKAN 1: Impor trait HasApiTokens dari Laravel Sanctum
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Opsional bawaan Laravel untuk notifikasi

class User extends Authenticatable
{
    // PERBAIKAN 2: Masukkan HasApiTokens di dalam statement use ini
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambahan standar Laravel untuk keamanan casting password otomatis
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
