@php
    $heroContent = isset($homepageBrandings)
        ? $homepageBrandings->firstWhere('jenis_konten', 'profil_toko')
        : null;

    $heroTitle = $heroContent?->nama_konten ?: 'Profil Toko Long Black';
    $heroSubtitle = $heroContent?->nama_mitra ?: 'Profil Toko';
    $heroDescription = $heroContent?->deskripsi_konten ?: 'Tambahkan konten Profil Toko dari halaman admin Branding & Konten agar bagian utama homepage dapat berubah secara dinamis.';
    $heroImage = $heroContent?->logo_mitra ? Storage::url($heroContent->logo_mitra) : asset('img/banner/banner-img.png');
@endphp

<section class="homepage-hero-profile">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="homepage-hero-copy js-reveal">
                    <span>{{ $heroSubtitle }}</span>
                    <h1>{{ $heroTitle }}</h1>
                    <p>{{ $heroDescription }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="homepage-hero-media js-reveal">
                    <img src="{{ $heroImage }}" alt="{{ $heroTitle }}">
                </div>
            </div>
        </div>
    </div>
</section>
