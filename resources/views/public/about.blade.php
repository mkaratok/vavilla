@extends('public.layouts.master')

@section('title', 'Hakkımızda - Vellura Çeşme')

@section('content')
<!-- Page Header -->
<section class="page-header position-relative" style="height: 60vh; min-height: 400px; background-image: url('{{ asset('images/about-header.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5);"></div>
    <div class="container h-100 position-relative z-2 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="text-warning mb-2" style="font-size: 14px; letter-spacing: 3px; text-transform: uppercase;">The Cappa</div>
        <h1 class="display-3 text-white font-cormorant fw-bold">Hakkımızda</h1>
    </div>
</section>

<!-- About Content -->
<section class="about-content py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="{{ asset('images/about-1.jpg') }}" alt="Vellura Çeşme" class="img-fluid rounded">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-text">
                    <h2>Vellura Çeşme</h2>
                    <p>Her biri bir çiçeğin zarafetiyle adlandırılmış villalarımızla, misafirlerimize ev konforunda, huzurlu ve unutulmaz bir tatil deneyimi sunmak için yola çıktık.</p>
                    <p>Begonvil, Petunya, Orkide, Papatya, Ortanca ve Menekşe isimlerini taşıyan villalarımız; sadece bir konaklama alanı değil, aynı zamanda her detayında özen ve sevgiyle hazırlanmış özel yaşam alanlarıdır.</p>
                    <p>Çeşme'nin eşsiz güzelliğinde, doğayla iç içe, lüks ve konforun buluştuğu villalarımızda sizi ağırlamaktan mutluluk duyarız.</p>
                </div>
            </div>
        </div>
        
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="about-image">
                    <img src="{{ asset('images/about-2.jpg') }}" alt="Vellura Çeşme" class="img-fluid rounded">
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="about-text">
                    <h3>Neden Biz?</h3>
                    <ul class="about-features">
                        <li><i class="fas fa-check"></i> Özel havuzlu lüks villalar</li>
                        <li><i class="fas fa-check"></i> 7/24 destek hizmeti</li>
                        <li><i class="fas fa-check"></i> Ücretsiz Wi-Fi</li>
                        <li><i class="fas fa-check"></i> Merkezi konum</li>
                        <li><i class="fas fa-check"></i> Tam donanımlı mutfak</li>
                        <li><i class="fas fa-check"></i> Bahçe ve teras alanları</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .page-header {
        height: 40vh;
        min-height: 300px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.6));
    }
    
    .page-header-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
    }
    
    .page-header-content h1 {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    .about-content {
        padding: 100px 0;
    }
    
    .about-image img {
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .about-text h2 {
        font-size: 36px;
        margin-bottom: 20px;
    }
    
    .about-text h3 {
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    .about-text p {
        color: #666;
        line-height: 1.8;
        margin-bottom: 15px;
    }
    
    .about-features {
        list-style: none;
        padding: 0;
    }
    
    .about-features li {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 0;
        color: #666;
        font-size: 16px;
    }
    
    .about-features i {
        color: var(--secondary-color);
        font-size: 18px;
    }
</style>
@endpush
