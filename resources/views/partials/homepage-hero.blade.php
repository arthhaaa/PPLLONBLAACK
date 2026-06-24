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
    <div class="homepage-floating-decor" aria-hidden="true">
        <span class="coffee-bean coffee-bean--one"></span>
        <span class="coffee-bean coffee-bean--two"></span>
        <span class="coffee-bean coffee-bean--three"></span>
        <span class="coffee-bean coffee-bean--four"></span>
        <span class="coffee-steam coffee-steam--one"></span>
        <span class="coffee-steam coffee-steam--two"></span>
    </div>

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

        <div class="homepage-profile-highlights js-reveal" aria-label="Keunggulan Long Black">
            <div class="profile-highlight-card">
                <span class="lnr lnr-home"></span>
                <div>
                    <strong>Rumah Produksi Kopi</strong>
                    <p>Diolah dari proses yang rapi dan konsisten.</p>
                </div>
            </div>
            <div class="profile-highlight-card">
                <span class="lnr lnr-leaf"></span>
                <div>
                    <strong>Origin Argopuro</strong>
                    <p>Karakter rasa khas lereng selatan Jember.</p>
                </div>
            </div>
            <div class="profile-highlight-card">
                <span class="lnr lnr-coffee-cup"></span>
                <div>
                    <strong>Fresh & Siap Seduh</strong>
                    <p>Produk dipilih untuk pengalaman minum terbaik.</p>
                </div>
            </div>
        </div>
    </div>
</section>
