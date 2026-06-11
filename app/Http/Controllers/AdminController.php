<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DataProduk;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProduk = DataProduk::count();
        $totalPelanggan = User::where('role', 'user')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        return view('admin.dashboard', compact('totalProduk', 'totalPelanggan', 'totalAdmin'));
    }

    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function laporan()
    {
        return view('admin.laporan');
    }

    public function transaksi()
    {
        return view('admin.transaksi');
    }
}