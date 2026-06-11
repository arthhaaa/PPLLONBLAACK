<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;

class BiayaOperasionalController extends Controller
{
    public function index()
    {
        $biayaOperasional = BiayaOperasional::latest('tanggal')
            ->latest()
            ->paginate(10);

        $totalBiaya = BiayaOperasional::sum('jumlah_biaya');
        $biayaBulanIni = BiayaOperasional::whereBetween('tanggal', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ])->sum('jumlah_biaya');

        $kategoriTerbesar = BiayaOperasional::selectRaw('jenis_biaya, SUM(jumlah_biaya) as total')
            ->groupBy('jenis_biaya')
            ->orderByDesc('total')
            ->first();

        return view('admin.biaya-operasional.index', compact(
            'biayaOperasional',
            'totalBiaya',
            'biayaBulanIni',
            'kategoriTerbesar'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_biaya' => 'required|string|max:255',
            'nama_biaya' => 'required|string|max:255',
            'jumlah_biaya' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data['username'] = auth()->user()->username ?? auth()->user()->name ?? 'Admin';

        BiayaOperasional::create($data);

        return redirect()->route('admin.biaya-operasional.index')
            ->with('success', 'Biaya operasional berhasil ditambahkan.');
    }

    public function update(Request $request, BiayaOperasional $biaya_operasional)
    {
        $data = $request->validate([
            'jenis_biaya' => 'required|string|max:255',
            'nama_biaya' => 'required|string|max:255',
            'jumlah_biaya' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $biaya_operasional->update($data);

        return redirect()->route('admin.biaya-operasional.index')
            ->with('success', 'Biaya operasional berhasil diperbarui.');
    }

    public function destroy(BiayaOperasional $biaya_operasional)
    {
        $biaya_operasional->delete();

        return redirect()->route('admin.biaya-operasional.index')
            ->with('success', 'Biaya operasional berhasil dihapus.');
    }
}
