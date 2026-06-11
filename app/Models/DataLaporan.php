<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataLaporan extends Model
{
    protected $table = 'data_laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_pelanggan', 
        'id_transaksi', 
        'nama_biaya', 
        'jumlah_biaya', 
        'keterangan_biaya', 
        'metode_pembayaran', 
        'status_transaksi', 
        'tanggal_biaya', 
        'total_harga'
    ];
}