@extends('public.layouts.master')

@section('title', 'İletişim - Vellura Çeşme')

@section('content')
<!-- Page Header -->
<section class="page-header position-relative" style="height: 60vh; min-height: 400px; background-image: url('{{ asset('images/contact-header.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5);"></div>
    <div class="container h-100 position-relative z-2 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="text-warning mb-2" style="font-size: 14px; letter-spacing: 3px; text-transform: uppercase;">The Cappa</div>
        <h1 class="display-3 text-white font-cormorant fw-bold">İletişim</h1>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-content py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="contact-info">
                    <h3>İletişim Bilgileri</h3>
                    
                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="text">
                            <h5>Adres</h5>
                            <p>{{ $settings->iletisim_adres ?? 'Çiftlik Köy Çeşme / İzmir, Türkiye' }}</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="text">
                            <h5>Telefon</h5>
                            <p>{{ $settings->telefon1 ?? '0552 786 78 07' }}</p>
                            @if($settings->telefon2 ?? false)
                            <p>{{ $settings->telefon2 }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="text">
                            <h5>E-posta</h5>
                            <p>{{ $settings->iletisim_email ?? 'info@cesmevellura.com' }}</p>
                        </div>
                    </div>
                    
                    <div class="social-links mt-4">
                        @if($settings->facebook ?? false)
                        <a href="{{ $settings->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($settings->instagram ?? false)
                        <a href="{{ $settings->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($settings->twitter ?? false)
                        <a href="{{ $settings->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form">
                    <h3>Mesaj Gönderin</h3>
                    
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="ad" class="form-control" placeholder="Adınız" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="soyad" class="form-control" placeholder="Soyadınız" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="E-posta Adresiniz" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="tel" name="telefon" class="form-control" placeholder="Telefon Numaranız">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="konu" class="form-control" placeholder="Konu" required>
                        </div>
                        <div class="form-group">
                            <textarea name="mesaj" class="form-control" rows="5" placeholder="Mesajınız" required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Mesaj Gönder</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Map -->
        @if($settings->harita ?? false)
        <div class="contact-map mt-5">
            {!! $settings->harita !!}
        </div>
        @endif
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
    
    .contact-content {
        padding: 100px 0;
        color: #fff;
    }
    
    .contact-info h3,
    .contact-form h3 {
        font-size: 28px;
        margin-bottom: 30px;
        color: #fff;
    }
    
    .info-item {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }
    
    .info-item .icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-accent);
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .info-item h5 {
        font-size: 16px;
        margin-bottom: 5px;
        color: #fff;
    }
    
    .info-item p {
        color: #888;
        margin: 0;
    }
    
    .contact-info .social-links a {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        margin-right: 10px;
        transition: all 0.3s ease;
    }
    
    .contact-info .social-links a:hover {
        background: var(--gold-accent);
        color: #000;
        border-color: var(--gold-accent);
    }
    
    .contact-form {
        background: #1a1a1a;
        padding: 40px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .contact-form .form-group {
        margin-bottom: 20px;
    }
    
    .contact-form .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 5px;
        font-size: 14px;
        background: rgba(0,0,0,0.2);
        color: #fff;
    }
    
    .contact-form .form-control:focus {
        border-color: var(--gold-accent);
        box-shadow: none;
        background: rgba(0,0,0,0.4);
    }
    
    .contact-form .form-control::placeholder {
        color: #666;
    }
    
    .contact-form textarea.form-control {
        resize: none;
    }
    
    .btn-submit {
        background: var(--gold-accent);
        color: #000;
        padding: 15px 40px;
        border: none;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 1px;
    }
    
    .btn-submit:hover {
        background: #fff;
        color: #000;
    }
    
    .contact-map {
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .contact-map iframe {
        width: 100%;
        height: 100%;
        border: 0;
        filter: grayscale(100%) invert(92%) contrast(83%);
    }
</style>
@endpush
