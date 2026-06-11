<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt Login
        if (Auth::attempt([
            'username' => $request->username, 
            'password' => $request->password
        ], $request->filled('remember'))) {
            
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Selamat datang Admin!');
            } 

            // Customer
            return redirect()->route('customer.home')
                             ->with('success', 'Login berhasil! Selamat datang kembali ☕');
        }

        // Jika gagal login
        return back()
               ->withErrors(['username' => 'Username atau password salah.'])
               ->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}