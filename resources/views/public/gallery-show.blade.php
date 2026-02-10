@extends('public.layouts.master')

@section('title', $gallery->baslik . ' - Galeri - Vavilla Çeşme')

@section('content')
<!-- Page Header -->
<section class="page-header position-relative" style="height: 50vh; min-height: 400px; background-image: url('{{ $gallery->images->first() ? asset('storage/gallery/' . $gallery->images->first()->bresim) : asset('images/gallery-header.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6);"></div>
    <div class="container h-100 position-relative z-2 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="text-warning mb-2" style="font-size: 14px; letter-spacing: 3px; text-transform: uppercase;">Galeri</div>
        <h1 class="display-3 text-white font-cormorant fw-bold">{{ $gallery->baslik }}</h1>
    </div>
</section>

<!-- Gallery Content -->
<section class="gallery-content py-5" style="background-color: var(--dark-bg); color: #fff;">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('gallery') }}" class="text-white-50 text-decoration-none small text-uppercase fw-bold"><i class="fas fa-arrow-left me-2"></i> Galerilere Dön</a>
        </div>
        
        <div class="row gallery-grid">
            @forelse($gallery->images as $image)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="gallery-item position-relative overflow-hidden rounded">
                    <a href="{{ asset('storage/gallery/' . $image->bresim) }}" data-lightbox="gallery-{{ $gallery->id }}" data-title="{{ $gallery->baslik }}">
                        <img src="{{ asset('storage/gallery/' . $image->kresim) }}" alt="{{ $gallery->baslik }}" class="w-100" style="height: 300px; object-fit: cover; transition: transform 0.5s ease;">
                        <div class="gallery-overlay d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-search-plus fa-2x text-white mb-2"></i>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-white-50">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <h4>Bu galeride henüz görsel bulunmuyor.</h4>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    /* Lightbox Z-Index Fix */
    .lightboxOverlay {
        z-index: 99998 !important;
    }
    .lightbox {
        z-index: 99999 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': 'Görsel %1 / %2'
    });
</script>
@endpush
