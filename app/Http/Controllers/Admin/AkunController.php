<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    /**
     * Menampilkan Profil Admin Sendiri (Read Only)
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.akun.profile', compact('user'));
    }

    /**
     * Menampilkan Daftar Semua Akun Pelanggan (Read Only)
     */
    public function index()
    {
        $users = User::where('role', 'user')
                     ->latest()
                     ->paginate(15);
                     
        return view('admin.akun.index', compact('users'));
    }
}