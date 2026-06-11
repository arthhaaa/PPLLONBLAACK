<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'telp'      => 'required|string|max:20',
            'alamat'    => 'required|string',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'telp'      => $request->telp,
            'alamat'    => $request->alamat,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('customer.home')
                         ->with('success', 'Registrasi berhasil! Selamat datang di Long Black ☕');
    }
}