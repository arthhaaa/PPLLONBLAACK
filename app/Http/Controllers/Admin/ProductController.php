<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = DataProduk::latest()->paginate(10);
        $availableCount = DataProduk::whereRaw('CAST(stok_produk AS UNSIGNED) > 0')->count();
        $lowStockCount = DataProduk::whereRaw('CAST(stok_produk AS UNSIGNED) BETWEEN 1 AND 10')->count();
        $trashedProducts = DataProduk::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(8, ['*'], 'terhapus');
        $trashedCount = DataProduk::onlyTrashed()->count();

        return view('admin.produk.index', compact('products', 'availableCount', 'lowStockCount', 'trashedProducts', 'trashedCount'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'      => 'required|string|max:255',
            'deskripsi_produk' => 'required|string',
            'harga_produk'     => 'required|numeric|min:0',
            'stok_produk'      => 'required|integer|min:0',
            'gambar_produk'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('gambar_produk');

        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request->file('gambar_produk')->store('products', 'public');
        }

        DataProduk::create($data);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(DataProduk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function show(DataProduk $produk)
    {
        return redirect()->route('admin.produk.edit', $produk);
    }

    public function update(Request $request, DataProduk $produk)
    {
        $request->validate([
            'nama_produk'      => 'required|string|max:255',
            'deskripsi_produk' => 'required|string',
            'harga_produk'     => 'required|numeric|min:0',
            'stok_produk'      => 'required|integer|min:0',
            'gambar_produk'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('gambar_produk');

        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama
            if ($produk->gambar_produk) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }
            $data['gambar_produk'] = $request->file('gambar_produk')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(DataProduk $produk)
    {
        $produk->delete();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    public function restore($id)
    {
        $produk = DataProduk::onlyTrashed()->findOrFail($id);
        $produk->restore();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $produk = DataProduk::onlyTrashed()->findOrFail($id);

        if ($produk->gambar_produk) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->forceDelete();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil dihapus permanen!');
    }
}
