<?php

namespace App\Http\Controllers;

use App\Models\BrandingEdukasi;
use App\Models\DataProduk;

class GuestController extends Controller
{
    public function index()
    {
        $featuredProducts = DataProduk::latest()->take(8)->get();
        $homepageBrandings = BrandingEdukasi::active()
            ->visibleFor('guest')
            ->orderBy('urutan')
            ->latest()
            ->take(12)
            ->get();
    
        return view('guest.index', compact('featuredProducts', 'homepageBrandings'));
    }

}
