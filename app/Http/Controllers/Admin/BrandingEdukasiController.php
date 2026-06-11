<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandingEdukasi;
use App\Models\DataProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BrandingEdukasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brandings = BrandingEdukasi::orderBy('urutan')->latest()->paginate(10);
        return view('admin.branding.index', compact('brandings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contentTypes = BrandingEdukasi::CONTENT_TYPES;

        return view('admin.branding.create', compact('contentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra'   => 'required|string|max:255',
            'nama_konten'  => 'required|string|max:255',
            'jenis_konten' => 'required|in:' . implode(',', array_keys(BrandingEdukasi::CONTENT_TYPES)),
            'deskripsi_konten' => 'nullable|string|max:1500',
            'logo_mitra'   => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'video_konten' => 'nullable|url|max:500',
            'link_konten'  => 'nullable|url|max:500',
            'tampil_di'    => 'required|in:guest,customer,both',
            'is_active'    => 'nullable|boolean',
            'urutan'       => 'nullable|integer|min:0|max:999',
        ]);

        $data = $request->only([
            'nama_mitra',
            'nama_konten',
            'jenis_konten',
            'deskripsi_konten',
            'video_konten',
            'link_konten',
            'tampil_di',
            'urutan',
        ]);
        $data['username'] = Auth::user()->username;
        $data['is_active'] = $request->boolean('is_active');

        // Upload Logo
        if ($request->hasFile('logo_mitra')) {
            $data['logo_mitra'] = $request->file('logo_mitra')->store('branding/logo', 'public');
        }

        BrandingEdukasi::create($data);

        return redirect()->route('admin.branding.index')
                         ->with('success', 'Konten Branding & Edukasi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BrandingEdukasi $branding)
    {
        $contentTypes = BrandingEdukasi::CONTENT_TYPES;

        return view('admin.branding.edit', compact('branding', 'contentTypes'));
    }

    public function preview(BrandingEdukasi $branding)
    {
        return $this->renderHomepagePreview($branding);
    }

    public function livePreview()
    {
        return $this->renderHomepagePreview(
            BrandingEdukasi::orderBy('urutan')->latest()->first()
        );
    }

    private function renderHomepagePreview(?BrandingEdukasi $branding = null)
    {
        $homepageBrandings = BrandingEdukasi::active()
            ->orderBy('urutan')
            ->latest()
            ->get();
        $featuredProducts = DataProduk::latest()->take(8)->get();
        $contentTypes = BrandingEdukasi::CONTENT_TYPES;

        return view('admin.branding.preview', compact('branding', 'homepageBrandings', 'featuredProducts', 'contentTypes'));
    }

    public function show(BrandingEdukasi $branding)
    {
        return redirect()->route('admin.branding.preview', $branding);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BrandingEdukasi $branding)
    {
        $request->validate([
            'nama_mitra'   => 'required|string|max:255',
            'nama_konten'  => 'required|string|max:255',
            'jenis_konten' => 'required|in:' . implode(',', array_keys(BrandingEdukasi::CONTENT_TYPES)),
            'deskripsi_konten' => 'nullable|string|max:1500',
            'logo_mitra'   => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'video_konten' => 'nullable|url|max:500',
            'link_konten'  => 'nullable|url|max:500',
            'tampil_di'    => 'required|in:guest,customer,both',
            'is_active'    => 'nullable|boolean',
            'urutan'       => 'nullable|integer|min:0|max:999',
        ]);

        $data = $request->only([
            'nama_mitra',
            'nama_konten',
            'jenis_konten',
            'deskripsi_konten',
            'video_konten',
            'link_konten',
            'tampil_di',
            'urutan',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        // Update Logo jika ada file baru
        if ($request->hasFile('logo_mitra')) {
            // Hapus logo lama
            if ($branding->logo_mitra) {
                Storage::disk('public')->delete($branding->logo_mitra);
            }
            $data['logo_mitra'] = $request->file('logo_mitra')->store('branding/logo', 'public');
        }

        $branding->update($data);

        return redirect()->route('admin.branding.index')
                         ->with('success', 'Konten Branding & Edukasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BrandingEdukasi $branding)
    {
        // Hapus logo jika ada
        if ($branding->logo_mitra) {
            Storage::disk('public')->delete($branding->logo_mitra);
        }

        $branding->delete();

        return redirect()->route('admin.branding.index')
                         ->with('success', 'Konten Branding berhasil dihapus!');
    }
}
