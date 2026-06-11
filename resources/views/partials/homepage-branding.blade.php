@php
    $homepageContent = isset($homepageBrandings) ? $homepageBrandings->groupBy('jenis_konten') : collect();
    $storeProfile = $homepageContent->get('profil_toko', collect())->first();
    $productDescriptions = $homepageContent->get('deskripsi_produk', collect());
    $collaborations = $homepageContent->get('kerja_sama', collect());
    $awards = $homepageContent->get('penghargaan', collect());
    $educations = $homepageContent->get('edukasi_kopi', collect());
    $showEmptySections = $showEmptySections ?? false;
    $hasCmsSections = $productDescriptions->isNotEmpty()
        || $collaborations->isNotEmpty()
        || $awards->isNotEmpty()
        || $educations->isNotEmpty();
@endphp

@if($hasCmsSections || $showEmptySections)
    <section class="homepage-cms-area section_gap">
        <div class="container">
            @if($productDescriptions->isNotEmpty())
                <div class="cms-section-heading js-reveal">
                    <span>Produk</span>
                    <h2>Deskripsi Produk</h2>
                </div>

                <div class="row">
                    @foreach($productDescriptions as $item)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <article class="cms-content-card js-reveal">
                                <div class="cms-card-media">
                                    @if($item->logo_mitra)
                                        <img src="{{ Storage::url($item->logo_mitra) }}" alt="{{ $item->nama_konten }}">
                                    @else
                                        <span class="lnr lnr-coffee-cup"></span>
                                    @endif
                                </div>
                                <div class="cms-card-body">
                                    <span>{{ $item->nama_mitra }}</span>
                                    <h3>{{ $item->nama_konten }}</h3>
                                    <p>{{ Str::limit($item->deskripsi_konten ?: 'Deskripsi produk kopi Long Black.', 140) }}</p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @elseif($showEmptySections)
                <div class="cms-empty-section js-reveal">
                    <span>Deskripsi Produk</span>
                    <h2>Section deskripsi produk belum diisi</h2>
                    <p>Tambahkan narasi produk, foto produk unggulan, atau penjelasan karakter kopi.</p>
                </div>
            @endif

            @if($collaborations->isNotEmpty())
                <div class="cms-split-section">
                    <div class="cms-section-heading is-left js-reveal">
                        <span>Kerja Sama</span>
                        <h2>Hasil Kerja Sama Toko</h2>
                    </div>

                    <div class="row">
                        @foreach($collaborations as $item)
                            <div class="col-lg-6 mb-4">
                                @if($item->link_konten)
                                    <a class="cms-wide-card cms-link-card js-reveal" href="{{ $item->link_konten }}" target="_blank" rel="noopener noreferrer">
                                @else
                                    <article class="cms-wide-card js-reveal">
                                @endif
                                    @if($item->logo_mitra)
                                        <img src="{{ Storage::url($item->logo_mitra) }}" alt="{{ $item->nama_konten }}">
                                    @endif
                                    <div>
                                        <span>{{ $item->nama_mitra }}</span>
                                        <h3>{{ $item->nama_konten }}</h3>
                                        <p>{{ Str::limit($item->deskripsi_konten ?: 'Cerita hasil kerja sama toko.', 170) }}</p>
                                        @if($item->link_konten)
                                            <small class="cms-link-hint">Lihat detail <i class="fa fa-external-link"></i></small>
                                        @endif
                                    </div>
                                @if($item->link_konten)
                                    </a>
                                @else
                                    </article>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($showEmptySections)
                <div class="cms-empty-section js-reveal">
                    <span>Kerja Sama</span>
                    <h2>Section hasil kerja sama belum diisi</h2>
                    <p>Gunakan section ini untuk menampilkan mitra, kolaborasi, atau hasil kerja sama toko.</p>
                </div>
            @endif

            @if($awards->isNotEmpty())
                <div class="cms-section-heading js-reveal">
                    <span>Penghargaan</span>
                    <h2>Penghargaan yang Diperoleh Toko</h2>
                </div>

                <div class="cms-award-grid">
                    @foreach($awards as $item)
                        @if($item->link_konten)
                            <a class="cms-award-card cms-link-card js-reveal" href="{{ $item->link_konten }}" target="_blank" rel="noopener noreferrer">
                        @else
                            <article class="cms-award-card js-reveal">
                        @endif
                            @if($item->logo_mitra)
                                <img src="{{ Storage::url($item->logo_mitra) }}" alt="{{ $item->nama_konten }}">
                            @else
                                <span class="lnr lnr-star"></span>
                            @endif
                            <div>
                                <small>{{ $item->nama_mitra }}</small>
                                <h3>{{ $item->nama_konten }}</h3>
                                <p>{{ Str::limit($item->deskripsi_konten ?: 'Penghargaan Long Black.', 120) }}</p>
                                @if($item->link_konten)
                                    <small class="cms-link-hint">Lihat detail <i class="fa fa-external-link"></i></small>
                                @endif
                            </div>
                        @if($item->link_konten)
                            </a>
                        @else
                            </article>
                        @endif
                    @endforeach
                </div>
            @elseif($showEmptySections)
                <div class="cms-empty-section js-reveal">
                    <span>Penghargaan</span>
                    <h2>Section penghargaan belum diisi</h2>
                    <p>Tambahkan penghargaan, sertifikat, atau pencapaian yang pernah diperoleh toko.</p>
                </div>
            @endif

            @if($educations->isNotEmpty())
                <div class="cms-education-section">
                    <div class="cms-section-heading is-left js-reveal">
                        <span>Edukasi Kopi</span>
                        <h2>Pentingnya Kopi</h2>
                    </div>

                    <div class="row">
                        @foreach($educations as $item)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <article class="cms-education-card js-reveal">
                                    @if($item->logo_mitra)
                                        <img src="{{ Storage::url($item->logo_mitra) }}" alt="{{ $item->nama_konten }}">
                                    @endif
                                    <div>
                                        <span>{{ $item->nama_mitra }}</span>
                                        <h3>{{ $item->nama_konten }}</h3>
                                        <p>{{ Str::limit($item->deskripsi_konten ?: 'Konten edukasi mengenai kopi.', 135) }}</p>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($showEmptySections)
                <div class="cms-empty-section js-reveal">
                    <span>Edukasi Kopi</span>
                    <h2>Section edukasi kopi belum diisi</h2>
                    <p>Isi dengan konten edukasi mengenai pentingnya kopi, proses, manfaat, atau budaya minum kopi.</p>
                </div>
            @endif
        </div>
    </section>
@endif
