@extends('public.layouts.master')

@section('title', $villa->baslik . ' - Vavilla Çeşme')

@section('content')

<!-- 1. Hero / Header Slider -->
<section class="room-detail-header position-relative" style="height: 70vh; min-height: 500px;">
    @if($villa->images && $villa->images->count() > 0)
    <div class="swiper room-header-slider h-100">
        <div class="swiper-wrapper">
            <!-- Main Image -->
            <div class="swiper-slide">
                <div class="bg-image h-100 w-100" style="background-image: url('{{ asset('storage/villas/' . $villa->gorsel) }}'); background-size: cover; background-position: center;"></div>
                <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.3);"></div>
            </div>
            <!-- Gallery Images -->
            @foreach($villa->images as $image)
            <div class="swiper-slide">
                <div class="bg-image h-100 w-100" style="background-image: url('{{ asset('storage/villas/gallery/' . $image->dosya) }}'); background-size: cover; background-position: center;"></div>
                <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.3);"></div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
    @else
    <div class="bg-image h-100 w-100" style="background-image: url('{{ asset('images/villa-placeholder.jpg') }}'); background-size: cover; background-position: center;"></div>
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.3);"></div>
    @endif
    
    <div class="container h-100 position-relative z-2">
        <div class="d-flex flex-column justify-content-center h-100 text-center">
            <div class="text-warning mb-2 letter-spacing-3 text-uppercase">Luxury Hotel</div>
            <h1 class="display-3 text-white font-cormorant fw-bold">{{ $villa->baslik }}</h1>
        </div>
    </div>
</section>

<!-- 2. Main Content Area -->
<section class="room-details py-5 bg-darker" style="background-color: #111; position: relative; z-index: 10;">
    <div class="container py-5">
        <div class="row">
            <!-- Left Column: Details -->
            <div class="col-lg-8">
                <!-- Rating -->
                <div class="mb-3 text-warning">
                    @for($i=0; $i<5; $i++) <i class="fas fa-star sm"></i> @endfor
                    <span class="ms-2 text-white-50 text-uppercase letter-spacing-2" style="font-size: 12px;">5.0 (Mükemmel)</span>
                </div>
                
                <h2 class="text-white font-cormorant display-5 mb-4">{{ $villa->baslik }}</h2>
                
                <div class="text-white-50 mb-5 leading-relaxed">
                    {!! $villa->aciklama !!}
                </div>

                <!-- Policies Grid -->
                <div class="row mb-5 gy-4">
                    <div class="col-md-6">
                        <div class="policy-card bg-dark bg-opacity-25 p-4 rounded h-100 border border-secondary border-opacity-10">
                            <h4 class="text-white font-cormorant mb-3"><i class="fas fa-clock text-warning me-2"></i> Giriş-Çıkış</h4>
                            <ul class="list-unstyled text-white-50 small mb-0">
                                <li class="mb-2 d-flex justify-content-between">
                                    <span>Giriş Saati:</span>
                                    <span class="text-white">14:00 ve sonrası</span>
                                </li>
                                <li class="mb-2 d-flex justify-content-between">
                                    <span>Çıkış Saati:</span>
                                    <span class="text-white">10:00 ve öncesi</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Erken Giriş:</span>
                                    <span class="text-white">Müsaitliğe bağlı</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="policy-card bg-dark bg-opacity-25 p-4 rounded h-100 border border-secondary border-opacity-10">
                            <h4 class="text-white font-cormorant mb-3"><i class="fas fa-paw text-warning me-2"></i> Evcil Hayvan</h4>
                            <p class="text-white-50 small mb-0">Evcil hayvan kabul edilmemektedir.</p>
                            
                            <h4 class="text-white font-cormorant mt-4 mb-2"><i class="fas fa-child text-warning me-2"></i> Çocuklar</h4>
                            <p class="text-white-50 small mb-0">Her yaştan çocuk kabul edilir. Ek yatak ücreti uygulanabilir.</p>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="alert alert-dark border-start border-warning border-4 text-black-50 small">
                            <i class="fas fa-info-circle text-warning me-2"></i>
                            Giriş talimatları varıştan 5 gün önce e-posta ile gönderilecektir. Resepsiyon personeli sizi karşılayacaktır.
                        </div>
                    </div>
                </div>

                <a href="#booking-form" class="btn btn-gold px-5 py-3">Müsaitlik Kontrol Et</a>
                @if(!empty($settings->telefon1))
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->telefon1) }}" class="btn btn-outline-gold px-5 py-3 ms-3">
                    <i class="fas fa-phone me-2"></i>Telefon ile Randevu
                </a>
                @endif
            </div>

            <!-- Right Column: İmkanlar -->
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="ps-lg-5">
                    <h4 class="text-white font-cormorant mb-4">İmkanlar</h4>
                    
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <div class="p-3 bg-dark bg-opacity-50 border border-secondary border-opacity-10 rounded text-center">
                                <i class="fas fa-user-friends text-warning fa-2x mb-2"></i>
                                <div class="text-white">{{ $villa->kisi_sayisi }} Kişilik</div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-bed text-warning me-2 w-25px"></i> 
                                <span class="text-white">{{ $villa->yatak_odasi }} Yatak Odası</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-bath text-warning me-2 w-25px"></i>
                                <span class="text-white">{{ $villa->banyo_sayisi }} Banyo</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-ruler-combined text-warning me-2 w-25px"></i>
                                <span class="text-white">{{ $villa->metrekare ?? 150 }} m²</span>
                            </div>
                        </div>
                        
                        @if($villa->wifi)
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-wifi text-warning me-2 w-25px"></i>
                                <span class="text-white">Wifi</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($villa->havuz)
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-swimming-pool text-warning me-2 w-25px"></i>
                                <span class="text-white">Havuz</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($villa->klima)
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-snowflake text-warning me-2 w-25px"></i>
                                <span class="text-white">Klima</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-parking text-warning me-2 w-25px"></i>
                                <span class="text-white">Otopark</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center text-white-50 small p-2">
                                <i class="fas fa-tv text-warning me-2 w-25px"></i>
                                <span class="text-white">TV</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Similar Rooms -->
@if(isset($similarVillas) && $similarVillas->count() > 0)
<section class="similar-rooms py-5" style="background-color: #0b0b0b;">
    <div class="container py-5">
        <div class="text-warning small mb-2 letter-spacing-2 text-uppercase">Likely To View</div>
        <h2 class="text-white font-cormorant mb-5">Similar Rooms</h2>
        
        <div class="row">
            @foreach($similarVillas as $similar)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="room-card position-relative overflow-hidden group">
                    <div class="image-wrapper position-relative" style="height: 400px;">
                        <img src="{{ $similar->gorsel ? asset('storage/villas/' . $similar->gorsel) : asset('images/villa-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover transition-transform duration-500">
                        <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
                    </div>
                    <div class="content position-absolute bottom-0 start-0 p-4 w-100">
                        <h4 class="text-white font-cormorant mb-1">{{ $similar->baslik }}</h4>
                        <div class="price text-warning small">{{ number_format($similar->fiyat, 0, ',', '.') }} ₺ / Gece</div>
                        <a href="{{ route('villas.show', $similar->sef) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 4. Ek Hizmetler -->
@if(isset($villaServices) && (is_countable($villaServices) ? count($villaServices) > 0 : false))
<section class="extras-section py-5" style="background-color: #111;">
    <div class="container py-5">
        <div class="text-warning small mb-2">En İyi Fiyat</div>
        <h2 class="text-white font-cormorant mb-5">Ek Hizmetler</h2>

        <div class="row">
            @foreach($villaServices as $service)
            <div class="col-md-6 mb-4">
                <div class="extra-card d-flex align-items-center bg-dark text-white p-0">
                    <div class="extra-img w-50" style="height: 250px;">
                        @if($service->ikon)
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(197, 164, 126, 0.2) 0%, rgba(0,0,0,0.5) 100%);">
                                <i class="{{ $service->ikon }} fa-5x text-warning"></i>
                            </div>
                        @else
                            <img src="{{ asset('images/about-1.jpg') }}" class="w-100 h-100" style="object-fit: cover;">
                        @endif
                    </div>
                    <div class="extra-content p-4 w-50">
                        <h4 class="font-cormorant">{{ $service->baslik }}</h4>
                        @if($service->fiyat > 0)
                            <div class="price text-warning mb-3">{{ number_format($service->fiyat, 0, ',', '.') }} ₺</div>
                        @endif
                        @if($service->aciklama)
                            <p class="text-white-50 small mb-0">{{ $service->aciklama }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 5. Booking Form -->
<section id="booking-form" class="bottom-area py-5" style="background-color: #111; background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-attachment: fixed; position: relative;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.8);"></div>
    <div class="container position-relative z-2 py-5">
        <div class="row">
            <!-- Testimonial -->
            <div class="col-lg-6 mb-5 mb-lg-0 text-white">
                <div class="mb-4 text-warning">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <h3 class="font-cormorant mb-4 line-height-1-4">Otelimizin her odasında özel banyo, Wi-Fi, kablolu televizyon bulunmaktadır ve kahvaltı fiyata dahildir.</h3>
                <div class="d-flex align-items-center mt-5">
                    <div class="video-btn d-flex align-items-center gap-3 cursor-pointer">
                        <div class="play-icon border border-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-play text-warning"></i>
                        </div>
                        <span class="text-uppercase small letter-spacing-2">Videoyu Oynat</span>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-lg-5 offset-lg-1">
                <div class="booking-form p-5" style="background-color: #0c0c0c; border: 1px solid rgba(255,255,255,0.1);">
                    <div class="text-warning small mb-2 text-uppercase letter-spacing-2">Rezervasyon Formu</div>
                    <h3 class="text-white font-cormorant mb-4">Rezervasyon Formu</h3>
                    
                    <form action="{{ route('reservation.create', $villa->sef) }}" method="GET">
                        <div class="mb-3">
                            <label class="text-white-50 small mb-1">Giriş Tarihi</label>
                            <input type="text" id="checkin_villa_detail" name="gelis" class="form-control bg-transparent border-secondary text-white p-3" placeholder="Giriş Tarihi Seçin">
                        </div>
                        <div class="mb-3">
                            <label class="text-white-50 small mb-1">Çıkış Tarihi</label>
                            <input type="text" id="checkout_villa_detail" name="cikis" class="form-control bg-transparent border-secondary text-white p-3" placeholder="Çıkış Tarihi Seçin">
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <select name="yetiskin" class="form-select bg-transparent border-secondary text-white p-3">
                                    <option value="1">1 Yetişkin</option>
                                    <option value="2">2 Yetişkin</option>
                                    <option value="3">3 Yetişkin</option>
                                    <option value="4">4 Yetişkin</option>
                                    <option value="5">5 Yetişkin</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select name="cocuk" class="form-select bg-transparent border-secondary text-white p-3">
                                    <option value="0">Çocuk Yok</option>
                                    <option value="1">1 Çocuk</option>
                                    <option value="2">2 Çocuk</option>
                                    <option value="3">3 Çocuk</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 py-3">MÜSAİTLİK KONTROL ET</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .font-cormorant { font-family: 'Cormorant Garamond', serif; }
    .letter-spacing-2 { letter-spacing: 2px; }
    .letter-spacing-3 { letter-spacing: 3px; }
    .bg-darker { background-color: #0b0b0b; }
    
    .room-header-slider .swiper-pagination-bullet { background: #fff; opacity: 0.5; }
    .room-header-slider .swiper-pagination-bullet-active { background: var(--gold-accent); opacity: 1; }
    
    .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .form-control:focus { background-color: rgba(255,255,255,0.05); color: #fff; border-color: var(--gold-accent); box-shadow: none; }
    .form-select option { background-color: #000; color: #fff; }

    /* Dark Theme for Flatpickr */
    .flatpickr-calendar {
        background: #1a1a1a !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
        border: 1px solid #333 !important;
    }
    .flatpickr-day {
        color: #ddd !important;
    }
    .flatpickr-day:hover {
        background: #333 !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: var(--gold-accent) !important;
        color: #000 !important;
        border-color: var(--gold-accent) !important;
    }
    .flatpickr-months .flatpickr-month {
        background: #1a1a1a !important;
        color: #fff !important;
        fill: #fff !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
        color: #fff !important;
    }
    .flatpickr-weekdays {
        background: #1a1a1a !important;
    }
    span.flatpickr-weekday {
        color: #888 !important;
    }
    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
        color: #fff !important;
        fill: #fff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize Swiper
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".mySwiper2", {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
</script>
<script>
    // Initialize Flatpickr (Datepicker) - Linked for villa detail form
    document.addEventListener('DOMContentLoaded', function() {
        const checkinVillaDetail = flatpickr("#checkin_villa_detail", {
            locale: "tr",
            dateFormat: "Y-m-d",
            minDate: "today",
            // defaultDate: "today", // Removed per user preference
            altInput: true,
            altFormat: "d F Y",
            theme: "dark",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutVillaDetail.set('minDate', nextDay);
                    
                    // Auto open checkout if empty
                    if (!document.getElementById('checkout_villa_detail').value) {
                        setTimeout(() => checkoutVillaDetail.open(), 100);
                    }
                }
            }
        });
        
        const checkoutVillaDetail = flatpickr("#checkout_villa_detail", {
            locale: "tr",
            dateFormat: "Y-m-d",
            minDate: "today",
            // defaultDate: "today", // Removed per user preference
            altInput: true,
            altFormat: "d F Y",
            theme: "dark"
        });
    });
</script>
@endpush
