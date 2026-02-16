@extends('public.layouts.master')

@section('title', 'Rezervasyon Başarılı')

@push('styles')
<style>
    .success-section {
        background-color: #111;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 120px 0 60px;
    }
    .success-card {
        background: #1a1a1a;
        border: 1px solid rgba(197, 164, 126, 0.2);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
    }
    .success-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(197, 164, 126, 0.1);
        border: 2px solid var(--gold-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .success-icon i {
        font-size: 3rem;
        color: var(--gold-accent);
    }
    .summary-box {
        background: #0c0c0c;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 15px;
        padding: 1.5rem;
        max-width: 500px;
        margin: 0 auto;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-row span {
        color: rgba(255,255,255,0.5);
    }
    .summary-row strong {
        color: #fff;
    }
    .summary-total {
        border-top: 1px solid rgba(197, 164, 126, 0.3) !important;
        border-bottom: none !important;
        padding-top: 1rem !important;
        margin-top: 0.5rem;
    }
    .summary-total strong {
        color: var(--gold-accent) !important;
        font-size: 1.25rem;
    }
    .info-alert {
        background: rgba(197, 164, 126, 0.08);
        border-left: 4px solid var(--gold-accent);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        text-align: left;
    }
    .info-alert i {
        color: var(--gold-accent);
    }

    @media (max-width: 576px) {
        .success-card { padding: 2rem 1.25rem; }
        .success-card h2 { font-size: 1.5rem; }
        .summary-box { padding: 1rem; }
    }
</style>
@endpush

@section('content')
<section class="success-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-2" style="font-family: 'Cormorant Garamond', serif;">Teşekkürler!</h2>
                    <p class="mb-4" style="color: rgba(255,255,255,0.6);">Rezervasyon talebiniz başarıyla alınmıştır.</p>
                    
                    <div class="summary-box mb-4">
                        <h5 class="fw-bold mb-3 text-white" style="font-size: 1rem; letter-spacing: 1px; text-transform: uppercase;">
                            <i class="fas fa-receipt me-2" style="color: var(--gold-accent);"></i>Rezervasyon Özeti
                        </h5>
                        <div class="summary-row">
                            <span>Villa:</span>
                            <strong>{{ $reservation->villa->baslik }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Giriş Tarihi:</span>
                            <strong>{{ $reservation->gelis_tarihi->format('d.m.Y') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Çıkış Tarihi:</span>
                            <strong>{{ $reservation->cikis_tarihi->format('d.m.Y') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Gece Sayısı:</span>
                            <strong>{{ $reservation->night_count }} gece</strong>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Toplam Tutar:</span>
                            <strong>{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</strong>
                        </div>
                    </div>
                    
                    <div class="info-alert mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        En kısa sürede sizinle iletişime geçeceğiz. Rezervasyonunuz onaylandığında e-posta ve SMS ile bilgilendirileceksiniz.
                    </div>
                    
                    <div class="mt-4 d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('home') }}" class="btn-gold px-4 py-3 d-inline-flex align-items-center justify-content-center" style="text-decoration: none;">
                            <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                        </a>
                        <a href="{{ route('villas.index') }}" class="btn-outline-gold px-4 py-3 d-inline-flex align-items-center justify-content-center" style="text-decoration: none;">
                            <i class="fas fa-building me-2"></i>Villaları İncele
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
