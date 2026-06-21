<?php

namespace App\Http\Controllers;

use App\Models\BrandingEdukasi;
use App\Models\DataProduk;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('customer.home');
        }

        $featuredProducts = DataProduk::latest()->take(8)->get();
        $homepageBrandings = BrandingEdukasi::active()
            ->visibleFor('guest')
            ->latest()
            ->take(12)
            ->get();
    
        return view('guest.index', compact('featuredProducts', 'homepageBrandings'));
    }

}
