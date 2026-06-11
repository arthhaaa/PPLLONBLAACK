<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pesanan', 
        'id_produk',
        'nama_pesanan', 
        'jumlah_produk', 
        'metode_pembayaran', 
        'tanggal_transaksi', 
        'total_harga'
    ];

    public function produk()
    {
        return $this->belongsTo(DataProduk::class, 'id_produk', 'id_produk')->withTrashed();
    }
}
