@extends('admin.layouts.master')

@section('title', 'Dashboard - Yönetim Paneli')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Toplam Villa -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalVillas }}</h3>
                <p>Toplam Villa</p>
            </div>
            <div class="icon">
                <i class="fas fa-home"></i>
            </div>
            <a href="{{ route('admin.villas.index') }}" class="small-box-footer">
                Detay <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Bekleyen Rezervasyonlar -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingReservations }}</h3>
                <p>Bekleyen Rezervasyon</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="small-box-footer">
                Detay <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Onaylı Rezervasyonlar -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $confirmedReservations }}</h3>
                <p>Onaylı Rezervasyon</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.reservations.index', ['status' => 'confirmed']) }}" class="small-box-footer">
                Detay <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Okunmamış Mesajlar -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $unreadMessages }}</h3>
                <p>Okunmamış Mesaj</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ route('admin.contact.index') }}" class="small-box-footer">
                Detay <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Son Rezervasyonlar -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Son Rezervasyonlar</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Villa</th>
                            <th>Müşteri</th>
                            <th>Giriş</th>
                            <th>Çıkış</th>
                            <th>Tutar</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestReservations as $reservation)
                        <tr>
                            <td>{{ $reservation->villa->baslik ?? 'Silinmiş Villa' }}</td>
                            <td>{{ $reservation->adsoyad }}</td>
                            <td>{{ $reservation->gelis_tarihi->format('d.m.Y') }}</td>
                            <td>{{ $reservation->cikis_tarihi->format('d.m.Y') }}</td>
                            <td>{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status_color }}">
                                    {{ $reservation->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Henüz rezervasyon yok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Hızlı İstatistikler -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Hızlı İstatistikler</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-3">
                    <p class="fw-bold mb-0">Toplam Bölge</p>
                    <span class="badge bg-primary">{{ $totalRegions }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-3">
                    <p class="fw-bold mb-0">Villa Tipleri</p>
                    <span class="badge bg-info">{{ $totalVillaTypes }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-3">
                    <p class="fw-bold mb-0">Teknik Özellikler</p>
                    <span class="badge bg-secondary">{{ $totalFeatures }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-3">
                    <p class="fw-bold mb-0">Müşteri Yorumları</p>
                    <span class="badge bg-success">{{ $totalReviews }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="fw-bold mb-0">E-Bülten Aboneleri</p>
                    <span class="badge bg-warning">{{ $totalNewsletters }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
