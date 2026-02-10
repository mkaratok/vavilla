@extends('public.layouts.master')

@section('title', 'Villalar & Süitler - Vavilla Çeşme')

@section('content')

<!-- 1. Page Header -->
<section class="page-header position-relative" style="height: 60vh; min-height: 400px; background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5);"></div>
    <div class="container h-100 position-relative z-2 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="text-warning mb-2" style="font-size: 14px; letter-spacing: 3px; text-transform: uppercase;">The Cappa</div> <!-- Static from design, can be dynamic -->
        <h1 class="display-3 text-white font-cormorant fw-bold">Rooms & Suites</h1>
    </div>
</section>

<!-- 2. Rooms Listing (Z-Pattern) -->
<section class="rooms-listing py-5" style="background-color: #111;">
    <div class="container py-5">
        @forelse($villas as $villa)
            @php $isEven = $loop->iteration % 2 == 0; @endphp
            <div class="room-item mb-5 mb-lg-0" style="margin-bottom: 120px !important;">
                <div class="row align-items-center {{ $isEven ? 'flex-row-reverse' : '' }}">
                    
                    <!-- Text Box -->
                    <div class="col-lg-6 position-relative z-2">
                        <div class="room-info p-5" style="background-color: #1b1b1b; {{ $isEven ? 'margin-left: -80px;' : 'margin-right: -80px;' }}">
                            <div class="price mb-2 text-warning" style="font-size: 14px; letter-spacing: 1px;">
                                {{ number_format($villa->fiyat, 0, ',', '.') }} ₺ / Gece
                            </div>
                            <h3 class="text-white font-cormorant mb-4" style="font-size: 32px;">{{ $villa->baslik }}</h3>
                            <p class="text-white-50 mb-4">
                                {{ Str::limit($villa->aciklama ?? 'Lüks ve konforun buluşma noktası. Özel havuzlu ve bahçeli bu villada unutulmaz bir tatil sizi bekliyor.', 120) }}
                            </p>
                            
                            <div class="room-features d-flex flex-wrap gap-4 mb-5 text-white-50 small">
                                <div><i class="fas fa-user-friends text-warning me-2"></i> {{ $villa->kisi_sayisi }} Kişi</div>
                                <div><i class="fas fa-bed text-warning me-2"></i> {{ $villa->yatak_odasi }} Yatak</div>
                                <div><i class="fas fa-ruler-combined text-warning me-2"></i> {{ $villa->metrekare ?? '150' }} m²</div>
                            </div>
                            
                            <div class="d-flex gap-3">
                                <a href="{{ route('villas.show', $villa->sef) }}" class="btn-outline-gold d-flex align-items-center justify-content-center">DETAYLAR <i class="fas fa-arrow-right ms-2"></i></a>
                                <a href="{{ route('villas.show', $villa->sef) }}" class="btn-gold d-flex align-items-center justify-content-center">REZERVASYON YAP</a>
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-6">
                        <div class="room-image position-relative overflow-hidden">
                            @if($villa->gorsel)
                                <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="{{ $villa->baslik }}" class="img-fluid w-100" style="height: 450px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/villa-placeholder.jpg') }}" alt="{{ $villa->baslik }}" class="img-fluid w-100" style="height: 450px; object-fit: cover;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
             <div class="text-center text-white py-5">
                <h3>Henüz villa eklenmemiş.</h3>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $villas->links() }}
        </div>
    </div>
</section>

<!-- 3. Search Rooms Section -->
<section class="search-section py-5" style="background-color: #0b0b0b;">
    <div class="container">
        <div class="text-center mb-5">
            <div class="text-warning small mb-2">CHECK AVAILABILITY</div>
            <h2 class="text-white font-cormorant">Search Rooms</h2>
        </div>
        
        <div class="search-box p-4" style="background-color: #1b1b1b; border: 1px solid rgba(255,255,255,0.05);">
            <form action="{{ route('villas.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="text-white-50 small mb-2">Giriş Tarihi</label>
                    <input type="text" class="form-control datepicker bg-dark text-white border-secondary" placeholder="Tarih Seçin">
                </div>
                <div class="col-md-3">
                    <label class="text-white-50 small mb-2">Çıkış Tarihi</label>
                    <input type="text" class="form-control datepicker bg-dark text-white border-secondary" placeholder="Tarih Seçin">
                </div>
                <div class="col-md-2">
                    <label class="text-white-50 small mb-2">Yetişkin</label>
                    <select class="form-select bg-dark text-white border-secondary">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-white-50 small mb-2">Çocuk</label>
                    <select class="form-select bg-dark text-white border-secondary">
                        <option>0</option>
                        <option>1</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-gold w-100 py-2">ARA</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- 4. Extra Services -->
<section class="extras-section py-5" style="background-color: #111;">
    <div class="container py-5">
        <div class="text-warning small mb-2">BEST PRICES</div>
        <h2 class="text-white font-cormorant mb-5">Extra Services</h2>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="extra-card d-flex align-items-center bg-dark text-white">
                    <div class="extra-img w-50" style="height: 250px;">
                        <img src="{{ asset('images/about-1.jpg') }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="extra-content p-4 w-50">
                        <h4 class="font-cormorant">Room Cleaning</h4>
                        <div class="price text-warning mb-3">500 ₺ <span class="text-muted small">/ aylık</span></div>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li><i class="fas fa-check text-warning me-2"></i> Günlük Temizlik</li>
                            <li><i class="fas fa-check text-warning me-2"></i> Havlu Değişimi</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="extra-card d-flex align-items-center bg-dark text-white">
                    <div class="extra-img w-50" style="height: 250px;">
                        <img src="{{ asset('images/about-2.jpg') }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="extra-content p-4 w-50">
                        <h4 class="font-cormorant">Drinks Included</h4>
                        <div class="price text-warning mb-3">300 ₺ <span class="text-muted small">/ günlük</span></div>
                        <ul class="list-unstyled text-white-50 small mb-0">
                            <li><i class="fas fa-check text-warning me-2"></i> Minibar Kullanımı</li>
                            <li><i class="fas fa-check text-warning me-2"></i> Sıcak İçecekler</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .font-cormorant { font-family: 'Cormorant Garamond', serif; }
    
    @media (max-width: 991px) {
        .room-info { margin: 0 !important; }
        .room-image { height: 300px !important; }
        .flex-row-reverse { flex-direction: column-reverse !important; }
    }
    
    .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .form-control:focus { background-color: rgba(255,255,255,0.05); color: #fff; border-color: var(--gold-accent); box-shadow: none; }
    .form-select option { background-color: #000; color: #fff; }
</style>
@endpush
