@php
    $instagramPosts = [
        [
            'url' => 'https://www.instagram.com/p/C7vtgwnOhGD/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
            'image' => asset('img/instagram/rkb1.jpg'),
            'alt' => 'Buah kopi merah di pohon',
        ],
        [
            'url' => 'https://www.instagram.com/p/C98wWWez6zM/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
            'image' => asset('img/instagram/rkb2.jpg'),
            'alt' => 'Produk kopi Cikasur Valley',
        ],
        [
            'url' => 'https://www.instagram.com/p/CrY9WCqStDg/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
            'image' => asset('img/instagram/rkb3.jpg'),
            'alt' => 'Biji kopi dijemur',
        ],
        [
            'url' => 'https://www.instagram.com/p/CtDg_HhSqpn/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
            'image' => asset('img/instagram/rkb4.jpg'),
            'alt' => 'Ranting kopi di kebun',
        ],
    ];
@endphp

<ul class="instafeed d-flex flex-wrap">
    @foreach($instagramPosts as $post)
        <li>
            <a href="{{ $post['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Buka post Instagram Long Black">
                <img src="{{ $post['image'] }}" alt="{{ $post['alt'] }}" loading="lazy">
                <span class="instagram-feed-icon"><i class="fa fa-instagram"></i></span>
            </a>
        </li>
    @endforeach
</ul>
