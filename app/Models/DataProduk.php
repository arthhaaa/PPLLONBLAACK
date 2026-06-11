<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk', 
        'deskripsi_produk', 
        'harga_produk', 
        'stok_produk',
        'gambar_produk' // Tambahkan gambar_produk ke fillable
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_produk', 'id_produk');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_produk', 'id_produk');
    }
}
