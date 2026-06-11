<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('branding_edukasi')
            ->where('jenis_konten', 'branding')
            ->update(['jenis_konten' => 'profil_toko']);

        DB::table('branding_edukasi')
            ->whereIn('jenis_konten', ['tentang_produk', 'highlight_produk'])
            ->update(['jenis_konten' => 'deskripsi_produk']);

        DB::table('branding_edukasi')
            ->where('jenis_konten', 'blog')
            ->update(['jenis_konten' => 'edukasi_kopi']);
    }

    public function down(): void
    {
        DB::table('branding_edukasi')
            ->where('jenis_konten', 'profil_toko')
            ->update(['jenis_konten' => 'branding']);

        DB::table('branding_edukasi')
            ->where('jenis_konten', 'deskripsi_produk')
            ->update(['jenis_konten' => 'tentang_produk']);

        DB::table('branding_edukasi')
            ->where('jenis_konten', 'edukasi_kopi')
            ->update(['jenis_konten' => 'blog']);
    }
};
