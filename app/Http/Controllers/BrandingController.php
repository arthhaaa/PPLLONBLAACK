<?php

namespace App\Http\Controllers;

use App\Models\BrandingEdukasi;

class BrandingController extends Controller
{
    public function index()
    {
        $brandings = BrandingEdukasi::active()->latest()->get();

        return view('branding.index', compact('brandings'));
    }

    public function show($id)
    {
        $branding = BrandingEdukasi::active()->findOrFail($id);

        return view('branding.show', compact('branding'));
    }
}
