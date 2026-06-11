@extends('layouts.app')

@section('content')
<section class="section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <a href="{{ route('branding.index') }}" class="btn btn-light mb-3">
                    ← Kembali ke Daftar Branding
                </a>

                <div class="card">
                    @if($branding->logo_mitra)
                        <div class="text-center pt-4">
                            <img src="{{ Storage::url($branding->logo_mitra) }}" 
                                 style="max-height: 180px; object-fit: contain;" alt="{{ $branding->nama_mitra }}">
                        </div>
                    @endif

                    <div class="card-body text-center">
                        <h2>{{ $branding->nama_mitra }}</h2>
                        <h4 class="text-primary">{{ $branding->nama_konten }}</h4>
                    </div>

                    @if($branding->video_konten)
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $branding->video_konten }}" 
                                    allowfullscreen 
                                    style="border-radius: 10px;"></iframe>
                        </div>
                    </div>
                    @endif

                    <div class="card-footer text-muted text-center">
                        Ditambahkan oleh: <strong>{{ $branding->username }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection