@extends('public.layouts.master')

@section('title', 'Galeri - Vavilla Çeşme')

@section('content')
<!-- Page Header -->
<section class="page-header position-relative" style="height: 60vh; min-height: 400px; background-image: url('{{ asset('images/gallery-header.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5);"></div>
    <div class="container h-100 position-relative z-2 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="text-warning mb-2" style="font-size: 14px; letter-spacing: 3px; text-transform: uppercase;">The Cappa</div>
        <h1 class="display-3 text-white font-cormorant fw-bold">Galeri</h1>
    </div>
</section>

<!-- Gallery Categories -->
<section class="gallery-categories py-5" style="background-color: var(--dark-bg); color: #fff;">
    <div class="container py-5">
        <div class="row">
            @forelse($galleries as $gallery)
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="gallery-card position-relative overflow-hidden rounded bg-dark border border-secondary border-opacity-10 shadow-lg">
                    <!-- Mini Slider -->
                    <div class="swiper gallery-mini-slider" style="height: 300px;">
                        <div class="swiper-wrapper">
                            @if($gallery->images->count() > 0)
                                @foreach($gallery->images as $img)
                                <div class="swiper-slide">
                                    <div class="bg-image w-100 h-100" style="background-image: url('{{ asset('storage/gallery/' . $img->kresim) }}'); background-size: cover; background-position: center;"></div>
                                </div>
                                @endforeach
                            @else
                                <div class="swiper-slide">
                                    <div class="bg-image w-100 h-100" style="background-image: url('{{ asset('images/about-1.jpg') }}'); background-size: cover; background-position: center;"></div>
                                </div>
                            @endif
                        </div>
                        <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25" style="z-index: 1; pointer-events: none;"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="card-body p-4 text-center position-relative z-2 bg-darker">
                        <h3 class="font-cormorant text-white mb-2">{{ $gallery->baslik }}</h3>
                        <p class="text-white-50 small mb-3">{{ $gallery->images->count() }} Fotoğraf</p>
                        <a href="{{ route('gallery.show', $gallery->sef) }}" class="btn-outline-gold btn-sm">GÖRÜNTÜLE</a>
                    </div>
                    
                    <!-- Clickable Area -->
                    <a href="{{ route('gallery.show', $gallery->sef) }}" class="stretched-link"></a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-white-50">
                <h3>Henüz galeri oluşturulmamış.</h3>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .gallery-card {
        transition: transform 0.3s ease;
    }
    .gallery-card:hover {
        transform: translateY(-5px);
    }
    .bg-darker {
        background-color: #0c0c0c;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swipers = document.querySelectorAll('.gallery-mini-slider');
        swipers.forEach(function(element) {
            new Swiper(element, {
                slidesPerView: 1,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                speed: 1000,
                allowTouchMove: false // Sadece görsel efekt, elle kaydırılmasın
            });
        });
    });
</script>
@endpush
