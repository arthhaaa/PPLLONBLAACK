<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'user_id',
        'kode_transaksi',
        'id_produk',
        'username', 
        'nama_produk', 
        'bentuk_produk',
        'metode_pembayaran', 
        'status_transaksi',
        'alamat_pengiriman',
        'catatan',
        'total_harga_produk', 
        'total_produk',
        'subtotal_produk',
        'ongkir',
        'kurir',
        'layanan_kurir',
        'destination_city_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'qris_url',
        'payment_payload',
        'payment_response',
        'dibatalkan_pada',
        'dibayar_pada',
    ];

    protected $casts = [
        'dibatalkan_pada' => 'datetime',
        'dibayar_pada' => 'datetime',
        'payment_payload' => 'array',
        'payment_response' => 'array',
    ];

    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('username', $user->username ?? $user->name);
        });
    }

    public function produk()
    {
        return $this->belongsTo(DataProduk::class, 'id_produk', 'id_produk')->withTrashed();
    }

    public function canBeModified(): bool
    {
        return in_array($this->status_transaksi, ['pending', 'menunggu_pembayaran'], true);
    }
}
