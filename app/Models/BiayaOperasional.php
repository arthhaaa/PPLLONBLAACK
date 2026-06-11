<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    protected $table = 'biaya_operasional';
    protected $primaryKey = 'id_biaya';

    protected $fillable = [
        'username', 
        'jenis_biaya', 
        'jumlah_biaya', 
        'keterangan', 
        'nama_biaya', 
        'tanggal'
    ];
}