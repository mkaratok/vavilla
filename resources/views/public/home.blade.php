@extends('public.layouts.master')

@section('title', 'Vavilla Çeşme - Lüks Villa Kiralama')

@push('styles')
<style>
/* Search Box Glow Effect */
.search-box-wrapper {
    position: relative;
    /* transition: all 0.3s ease; */
    animation: goldPulse 2s infinite ease-in-out; /* Outer Glow */
    border: 1px solid transparent; /* Hide default border to use gradient */
    background-clip: padding-box; /* Ensure bg doesn't overlap border area */
    border-radius: 0.375rem; /* Bootstrap rounded default */
    z-index: 1; /* Create stacking context */
    /* Ensure background is set to handle the clip correctly - assuming parent sets it or we set it here if needed */
    /* background-color: var(--dark-bg); If not inherited */ 
}

/* Shimmering Gold Border */
.search-box-wrapper::before {
    content: '';
    position: absolute;
    top: -2px; left: -2px; right: -2px; bottom: -2px; /* Slightly larger to be safe */
    background: linear-gradient(45deg, #c5a47e, #ffd700, #fff, #ffd700, #c5a47e);
    background-size: 400% 400%;
    z-index: -1; /* Behind the content/background */
    border-radius: 0.45rem; /* Slightly larger radius */
    animation: borderShimmer 3s linear infinite;
}

/* Outer Pulse Glow */
@keyframes goldPulse {
    0% {
        box-shadow: 0 0 10px rgba(197, 164, 126, 0.3);
    }
    50% {
        box-shadow: 0 0 25px rgba(255, 215, 0, 0.4), 0 0 50px rgba(197, 164, 126, 0.2);
    }
    100% {
        box-shadow: 0 0 10px rgba(197, 164, 126, 0.3);
    }
}

@keyframes borderShimmer {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}        border-color: rgba(197, 164, 126, 0.3);
    }
}

/* Flatpickr Dark Theme Overrides */
.flatpickr-calendar { background: #1a1a1a !important; border: 1px solid rgba(197, 164, 126, 0.3) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; }
.flatpickr-day { color: var(--gold-accent) !important; }
.flatpickr-day:hover { background: #333 !important; color: #fff !important; }
.flatpickr-day.selected { background: var(--gold-accent) !important; border-color: var(--gold-accent) !important; color: #000 !important; }
.flatpickr-day.today { border-color: var(--gold-accent) !important; }

/* Header & Text Colors */
.flatpickr-months .flatpickr-month { background: #1a1a1a !important; color: var(--gold-accent) !important; fill: var(--gold-accent) !important; }
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year { color: var(--gold-accent) !important; font-weight: 600; }
.flatpickr-current-month .flatpickr-monthDropdown-months:hover,
.flatpickr-current-month input.cur-year:hover { background: rgba(197, 164, 126, 0.1); }
.flatpickr-weekdays { background: #1a1a1a !important; }
span.flatpickr-weekday { color: var(--gold-accent) !important; }

/* Arrows */
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month { color: var(--gold-accent) !important; fill: var(--gold-accent) !important; }
.flatpickr-months .flatpickr-prev-month:hover svg, 
.flatpickr-months .flatpickr-next-month:hover svg { fill: #fff !important; }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover; background-position: center; height: 100vh; position: relative;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.3);"></div>
    <div class="container h-100 position-relative z-2">
        <div class="d-flex flex-column justify-content-center h-100 text-center text-white">
            <span class="d-block mb-3" style="font-family: 'Cormorant Garamond', serif; font-size: 24px; letter-spacing: 4px; text-transform: uppercase;">Lüks Bir Deneyimin Adresi</span>
            <h1 class="display-3 fw-bold mb-4" style="letter-spacing: 2px;">SİZİN İÇİN <br> <span style="color: var(--gold-accent);">MÜKEMMEL BİR ÜS</span></h1>
        </div>
    </div>
    
    <!-- Floating Search Box -->
    <div class="container position-relative z-3" style="margin-top: -80px; margin-bottom: 80px;">
        <div class="search-box-wrapper p-4 shadow-lg rounded" style="background: rgba(26, 26, 26, 0.95); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
            <form action="{{ route('villas.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Bölge</label>
                    <select name="bolge" class="form-select border-secondary bg-dark text-white p-3">
                        <option value="">Tümü</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->baslik }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Giriş Tarihi</label>
                    <input type="text" id="checkin_date" name="giris_tarihi" class="form-control border-secondary bg-dark text-white p-3 datepicker" placeholder="Tarih Seçin">
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Çıkış Tarihi</label>
                    <input type="text" id="checkout_date" name="cikis_tarihi" class="form-control border-secondary bg-dark text-white p-3 datepicker" placeholder="Tarih Seçin">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Kişi</label>
                    <select name="kisi_sayisi" class="form-select border-secondary bg-dark text-white p-3">
                        <option value="">Seçiniz</option>
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }} Kişi</option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-lg-1">
                    <label class="form-label d-block text-white-50 small">&nbsp;</label>
                    <button type="submit" class="btn btn-gold w-100 p-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Unique Luxury Experience Section -->
<section class="about-section" style="background-color: var(--dark-bg); color: #fff; padding-top: 150px !important; padding-bottom: 100px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <span class="section-subtitle">Hakkımızda</span>
                <h2 class="mb-4 display-5">{{ $about->baslik ?? 'Eşsiz Lüks Deneyim' }}</h2>
                <div class="row mt-5">
                    <div class="col-12 mb-4" style="color: #999; font-size: 14px; line-height: 1.8;">
                        @if($about && $about->icerik)
                            {!! $about->icerik !!}
                        @else
                            <p>Vavilla Çeşme, her detayıyla size özel tasarlanmış lüks villalarıyla unutulmaz bir tatil deneyimi sunuyor. Evinizin konforunu otel lüksüyle birleştiriyoruz.</p>
                            <p>Ege'nin incisi Çeşme'de, doğayla içe içe, modern ve konforlu villalarımızda huzuru keşfedin. Size özel havuzlar ve geniş yaşam alanları ile tatilin keyfini çıkarın.</p>
                        @endif
                    </div>
                </div>
                <a href="{{ url('/hakkimizda') }}" class="btn-gold mt-4">DAHA FAZLA</a>
            </div>
            
            <div class="col-lg-6 offset-lg-1 position-relative">
                <div class="row">
                    <div class="col-6 text-end" style="margin-top: 50px;">
                        <img src="{{ $about && $about->image_1 ? asset('storage/about/' . $about->image_1) : asset('images/about-1.jpg') }}" alt="{{ $about->baslik ?? 'Vavilla Interior' }}" class="img-fluid rounded" style="width: 100%; height: 400px; object-fit: cover; filter: brightness(0.8);">
                    </div>
                    <div class="col-6">
                        <img src="{{ $about && $about->image_2 ? asset('storage/about/' . $about->image_2) : asset('images/about-2.jpg') }}" alt="{{ $about->baslik ?? 'Vavilla Bedroom' }}" class="img-fluid rounded" style="width: 100%; height: 400px; object-fit: cover; filter: brightness(0.8);">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(\App\Models\Setting::first()->anasayfa_video)
<!-- Promotional Video Section -->
<section class="video-section position-relative" style="background-color: #000; padding: 0;">
    <div class="container-fluid p-0">
        <div class="video-container position-relative">
            <video id="promoVideo" width="100%" height="auto" muted loop playsinline style="display: block; width: 100%; max-height: 80vh; object-fit: cover;">
                <source src="{{ asset('storage/' . \App\Models\Setting::first()->anasayfa_video) }}" type="video/mp4">
                Tarayıcınız video etiketini desteklemiyor.
            </video>
            <div class="video-overlay position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-to-t from-black" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
               <div class="container">
                   <button id="videoControlBtn" class="btn btn-outline-gold rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                       <i class="fas fa-volume-mute"></i>
                   </button>
               </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('promoVideo');
    const btn = document.getElementById('videoControlBtn');
    const icon = btn.querySelector('i');
    
    if (video) {
        // Auto-play on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    video.play().catch(e => console.log('Autoplay prevented:', e));
                } else {
                    video.pause();
                }
            });
        }, { threshold: 0.5 }); // 50% görünür olunca oyna
        
        observer.observe(video);
        
        // Mute/Unmute Toggle
        btn.addEventListener('click', function() {
            if (video.muted) {
                video.muted = false;
                icon.classList.remove('fa-volume-mute');
                icon.classList.add('fa-volume-up');
            } else {
                video.muted = true;
                icon.classList.remove('fa-volume-up');
                icon.classList.add('fa-volume-mute');
            }
        });
    }
});
</script>
@endpush
@endif

<!-- Odalar & Villalar Section -->
<section class="rooms-section py-5" style="background-color: #0b0b0b;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-subtitle">Konaklama</span>
            <h2>Villalar & Süitler</h2>
        </div>
        
        <div class="row">
            @forelse($villas as $villa)
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="villa-card bg-transparent border-0">
                    <div class="card-image position-relative mb-3">
                        <a href="{{ route('villas.show', $villa->sef) }}">
                            @if($villa->gorsel)
                                <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="{{ $villa->baslik }}" class="w-100 rounded" style="height: 300px; object-fit: cover; filter: brightness(0.9);">
                            @else
                                <img src="{{ asset('images/villa-placeholder.jpg') }}" alt="{{ $villa->baslik }}" class="w-100 rounded" style="height: 300px; object-fit: cover; filter: brightness(0.9);">
                            @endif
                            <div class="price-badge position-absolute bottom-0 start-0 bg-white text-dark px-3 py-2" style="font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                                {{ number_format($villa->fiyat, 0, ',', '.') }} ₺ / Gece
                            </div>
                        </a>
                    </div>
                    <div class="card-body p-0 text-start">
                        <h3 class="h4 mb-2"><a href="{{ route('villas.show', $villa->sef) }}" class="text-white text-decoration-none">{{ $villa->baslik }}</a></h3>
                        <div class="d-flex gap-3 text-white-50 small mb-3">
                            <span><i class="fas fa-bed me-1" style="color: var(--gold-accent);"></i> {{ $villa->yatak_odasi ?? 3 }} Yatak Odası</span>
                            <span><i class="fas fa-shower me-1" style="color: var(--gold-accent);"></i> {{ $villa->banyo ?? 2 }} Banyo</span>
                            <span><i class="fas fa-user-friends me-1" style="color: var(--gold-accent);"></i> {{ $villa->kapasite ?? 6 }} Kişi</span>
                        </div>
                        <p class="text-white-50 small mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $villa->kisa_aciklama ?? 'Bu muhteşem villada unutulmaz bir tatil deneyimi sizi bekliyor. Özel havuz ve bahçe keyfi.' }}
                        </p>
                        <a href="{{ route('villas.show', $villa->sef) }}" class="btn-outline-gold">DETAYLARI GÖR</a>
                    </div>
                </div>
            </div>
            @empty
                <!-- Fallback if no villas found in DB for homepage -->
                 <div class="col-12 text-center text-white-50">
                    <p>Henüz villa eklenmemiş.</p>
                 </div>
            @endforelse
        </div>
    </div>
</section>



<!-- Hotel Facilities Section -->
<section class="facilities-section py-5" style="background-color: var(--dark-bg); padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="text-start mb-5">
            <span class="section-subtitle">Hizmetler</span>
            <h2>Otel Olanakları</h2>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-spa fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Spa Merkezi</h4>
                        <p class="text-white-50 small">Rahatlayın ve yenilenin. Profesyonel masaj ve bakım hizmetlerimizle kendinizi şımartın.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-dumbbell fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Spor Salonu</h4>
                        <p class="text-white-50 small">Tatilinizi yaparken formunuzu koruyun. Modern ekipmanlarla donatılmış salonumuz hizmetinizde.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-utensils fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Restoran</h4>
                        <p class="text-white-50 small">Ege'nin ve dünya mutfağının seçkin lezzetlerini deneyimleyin.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-swimmer fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Yüzme Havuzu</h4>
                        <p class="text-white-50 small">Özel ve ortak havuzlarımızda serinlemenin ve güneşin tadını çıkarın.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-wifi fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Fiber İnternet</h4>
                        <p class="text-white-50 small">Kesintisiz ve yüksek hızlı internet bağlantısı ile dünyadan kopmayın.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="facility-item d-flex gap-4">
                    <i class="fas fa-car fa-2x" style="color: var(--gold-accent);"></i>
                    <div>
                        <h4 class="mb-2">Otopark</h4>
                        <p class="text-white-50 small">Araçlarınız için güvenli ve ücretsiz otopark alanımız mevcuttur.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 position-relative" style="background-image: url('{{ asset('images/about-1.jpg') }}'); background-size: cover; background-position: center; padding-top: 100px; padding-bottom: 100px;">
    <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.8);"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center mb-5">
            <span class="section-subtitle">Görüşler</span>
            <h2>Misafirlerimiz Ne Diyor?</h2>
        </div>
        
        <div class="swiper testimonials-slider">
            <div class="swiper-wrapper">
                @forelse($reviews as $review)
                <div class="swiper-slide text-center p-4">
                    <div class="mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-warning small"></i>
                        @endfor
                    </div>
                    <p class="lead text-white mb-4 fst-italic">"{{ $review->yorum }}"</p>
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->isim) }}&background=c5a47e&color=fff" alt="{{ $review->isim }}" class="rounded-circle" width="60" height="60">
                        <div class="text-start">
                            <h5 class="mb-0 text-white">{{ $review->isim }}</h5>
                            <small class="text-white-50">Misafir</small>
                        </div>
                    </div>
                </div>
                @empty
                    <!-- Dummy Reviews if DB is empty -->
                    <div class="swiper-slide text-center p-4">
                        <div class="mb-4">
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                        </div>
                        <p class="lead text-white mb-4 fst-italic">"Harika bir tatildi, her şey çok güzeldi. Tavsiye ederim."</p>
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Ayse+Yilmaz&background=c5a47e&color=fff" alt="Ayşe Yılmaz" class="rounded-circle" width="60" height="60">
                            <div class="text-start">
                                <h5 class="mb-0 text-white">Ayşe Yılmaz</h5>
                                <small class="text-white-50">Misafir</small>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide text-center p-4">
                        <div class="mb-4">
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                            <i class="fas fa-star text-warning small"></i>
                        </div>
                        <p class="lead text-white mb-4 fst-italic">"Kesinlikle tekrar geleceğiz. Villa çok temiz ve lükstü."</p>
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Mehmet+Demir&background=c5a47e&color=fff" alt="Mehmet Demir" class="rounded-circle" width="60" height="60">
                            <div class="text-start">
                                <h5 class="mb-0 text-white">Mehmet Demir</h5>
                                <small class="text-white-50">Misafir</small>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="news-section py-5" style="background-color: var(--dark-bg); padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-subtitle">Blog</span>
                <h2>Son Haberler</h2>
            </div>
            <a href="#" class="btn-outline-gold">TÜMÜNÜ GÖR</a>
        </div>
        
        <div class="row">
            @forelse($blogs as $blog)
            <div class="col-lg-4 mb-4">
                <div class="blog-card">
                    <div class="card-image position-relative mb-3">
                        @if($blog->gorsel)
                            <img src="{{ asset('storage/blogs/' . $blog->gorsel) }}" alt="{{ $blog->baslik }}" class="w-100 rounded" style="height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/about-2.jpg') }}" alt="{{ $blog->baslik }}" class="w-100 rounded" style="height: 250px; object-fit: cover;">
                        @endif
                        <div class="date-badge position-absolute top-0 start-0 bg-dark text-white p-2 text-center" style="width: 60px;">
                            <span class="d-block fw-bold display-6">{{ $blog->created_at->format('d') }}</span>
                            <small class="d-block text-uppercase" style="font-size: 10px;">{{ $blog->created_at->format('M') }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3"><a href="#" class="text-white text-decoration-none">{{ $blog->baslik }}</a></h4>
                        <a href="#" class="text-gold text-decoration-none small text-uppercase fw-bold" style="color: var(--gold-accent); letter-spacing: 1px;">Devamını Oku <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @empty
                <!-- Dummy Blog Posts -->
                 <div class="col-lg-4 mb-4">
                <div class="blog-card">
                    <div class="card-image position-relative mb-3">
                        <img src="{{ asset('images/about-2.jpg') }}" alt="Blog Yazısı" class="w-100 rounded" style="height: 250px; object-fit: cover; opacity: 0.7;">
                        <div class="date-badge position-absolute top-0 start-0 bg-dark text-white p-2 text-center" style="width: 60px;">
                            <span class="d-block fw-bold display-6">12</span>
                            <small class="d-block text-uppercase" style="font-size: 10px;">ARA</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3"><a href="#" class="text-white text-decoration-none">Çeşme'de Tatil Keyfi</a></h4>
                        <p class="text-white-50 small">Çeşme'nin eşsiz koylarını keşfetmek için en iyi zaman.</p>
                        <a href="#" class="text-gold text-decoration-none small text-uppercase fw-bold" style="color: var(--gold-accent); letter-spacing: 1px;">Devamını Oku <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    var swiper = new Swiper(".testimonials-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>
@endpush
