<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class DataAkun extends Authenticatable
{
    protected $table = 'data_akun';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username', 'nama', 'email', 'password', 
        'alamat', 'telp', 'role'
    ];

    protected $hidden = ['password'];

    // Role Helper
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }
}