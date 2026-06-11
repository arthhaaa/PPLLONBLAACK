@extends('layouts.app')

@section('content')
<section class="section_gap">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Branding & Edukasi</h2>
            <p class="lead">Kenali mitra dan edukasi kopi bersama Long Black</p>
        </div>

        <div class="row">
            @forelse($brandings as $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($item->logo_mitra)
                        <img src="{{ Storage::url($item->logo_mitra) }}" 
                             class="card-img-top" style="height: 200px; object-fit: contain; padding: 20px;" alt="{{ $item->nama_mitra }}">
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->nama_mitra }}</h5>
                        <h6 class="text-primary">{{ $item->nama_konten }}</h6>
                        <p class="card-text text-muted small">
                            {{ Str::limit($item->nama_konten, 80) }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <a href="{{ route('branding.show', $item) }}" class="btn btn-outline-primary w-100">
                            Lihat Detail & Video
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>Belum ada konten branding saat ini</h4>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection