<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingEdukasi extends Model
{
    use HasFactory;

    public const CONTENT_TYPES = [
        'profil_toko' => 'Profil Toko',
        'deskripsi_produk' => 'Deskripsi Produk',
        'kerja_sama' => 'Hasil Kerja Sama Toko',
        'penghargaan' => 'Penghargaan Toko',
        'edukasi_kopi' => 'Edukasi Kopi',
    ];

    protected $table = 'branding_edukasi';
    
    protected $primaryKey = 'id_konten';

    protected $fillable = [
        'username',
        'nama_mitra',
        'logo_mitra',
        'nama_konten',
        'jenis_konten',
        'deskripsi_konten',
        'video_konten',
        'link_konten',
        'tampil_di',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisibleFor($query, string $page)
    {
        return $query->whereIn('tampil_di', [$page, 'both']);
    }

    public function getDisplayTargetLabelAttribute(): string
    {
        return match ($this->tampil_di) {
            'guest' => 'Guest',
            'customer' => 'Customer',
            default => 'Guest & Customer',
        };
    }

    public function getContentTypeLabelAttribute(): string
    {
        return self::CONTENT_TYPES[$this->jenis_konten] ?? match ($this->jenis_konten) {
            'tentang_produk' => 'Tentang Produk',
            'highlight_produk' => 'Highlight Produk',
            'blog' => 'Blog / Konten',
            'branding' => 'Branding',
            default => 'Konten Homepage',
        };
    }
}
