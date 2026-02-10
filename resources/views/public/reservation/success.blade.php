@extends('public.layouts.master')

@section('title', 'Rezervasyon Başarılı')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">Rezervasyon Talebi Alındı</h1>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm text-center" style="border-radius: 20px;">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Teşekkürler!</h2>
                        <p class="lead mb-4">Rezervasyon talebiniz başarıyla alınmıştır.</p>
                        
                        <div class="bg-light rounded-3 p-4 mb-4" style="max-width: 500px; margin: 0 auto;">
                            <h5 class="fw-bold mb-3">Rezervasyon Özeti</h5>
                            <div class="text-start">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Villa:</span>
                                    <strong>{{ $reservation->villa->baslik }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giriş Tarihi:</span>
                                    <strong>{{ $reservation->gelis_tarihi->format('d.m.Y') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Çıkış Tarihi:</span>
                                    <strong>{{ $reservation->cikis_tarihi->format('d.m.Y') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Gece Sayısı:</span>
                                    <strong>{{ $reservation->night_count }} gece</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>Toplam Tutar:</span>
                                    <strong class="text-success fs-5">{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            En kısa sürede sizinle iletişime geçeceğiz. Rezervasyonunuz onaylandığında e-posta ve SMS ile bilgilendirileceksiniz.
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('home') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
