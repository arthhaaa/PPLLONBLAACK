@extends('layouts.admin')

@section('title', 'Preview Homepage - Long Black')

@section('content')
@php
    $contentGroups = $homepageBrandings->groupBy('jenis_konten');
    $filledSections = collect($contentTypes)->keys()->filter(fn ($type) => $contentGroups->has($type))->count();
@endphp

<section class="admin-preview-bar">
    <div class="container-fluid px-4">
        <div class="admin-preview-inner">
            <div>
                <span class="preview-label">Live Preview</span>
                <strong>Homepage Guest & Customer</strong>
            </div>
            <div class="preview-meta">
                <span class="preview-badge">{{ $filledSections }}/{{ count($contentTypes) }} section terisi</span>
                <span class="preview-badge is-active">{{ $homepageBrandings->count() }} konten</span>
                @if($branding)
                    <a href="{{ route('admin.branding.edit', $branding) }}">Edit Konten Ini</a>
                @endif
                <a href="{{ route('admin.branding.index') }}">Kembali</a>
            </div>
        </div>
    </div>
</section>

<section class="homepage-preview-panel">
    <div class="container">
        <div class="preview-panel-card">
            <div class="preview-panel-copy">
                <span>Preview Tampilan</span>
                <h1>Homepage Long Black</h1>
                <p>Ini adalah pratinjau dari sudut pandang admin. Konten yang belum diisi akan ditandai sebagai placeholder agar mudah dilengkapi dari CRUD Branding & Konten.</p>
            </div>

            <div class="preview-section-map">
                @foreach($contentTypes as $type => $label)
                    <div class="preview-section-chip {{ $contentGroups->has($type) ? 'is-filled' : 'is-empty' }}">
                        <i class="fa {{ $contentGroups->has($type) ? 'fa-check' : 'fa-plus' }}"></i>
                        <span>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="admin-homepage-preview-frame">
    @include('partials.homepage-hero')
    @include('partials.homepage-branding', ['showEmptySections' => true, 'contentTypes' => $contentTypes])
    @include('partials.homepage-products', ['products' => $featuredProducts, 'requiresLogin' => true])
</div>
@endsection
