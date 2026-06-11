<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi_penjualan';

    protected $fillable = [
        'id_pelanggan', 
        'id_transaksi', 
        'id_produk',
        'metode_pembayaran', 
        'status_transaksi', 
        'total_transaksi'
    ];

    public function produk()
    {
        return $this->belongsTo(DataProduk::class, 'id_produk', 'id_produk')->withTrashed();
    }
}
