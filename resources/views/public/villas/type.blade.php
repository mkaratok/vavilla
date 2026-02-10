@extends('public.layouts.master')

@section('title', $type->baslik . ' - ' . ($settings->siteadi ?? 'Villa Kiralama'))
@section('description', $type->kisa ?? '')
@section('keywords', $type->kelime ?? '')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">{{ $type->baslik }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('villas.index') }}">Villalar</a></li>
                <li class="breadcrumb-item active">{{ $type->baslik }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($type->icerik)
        <div class="mb-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    {!! $type->icerik !!}
                </div>
            </div>
        </div>
        @endif
        
        <div class="row g-4">
            @forelse($villas as $villa)
            <div class="col-md-6 col-lg-3">
                <div class="villa-card">
                    <div class="villa-card-img">
                        @if($villa->gorsel)
                            <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="{{ $villa->baslik }}">
                        @else
                            <img src="https://via.placeholder.com/400x300/2c5f2d/ffffff?text=Villa" alt="">
                        @endif
                    </div>
                    <div class="villa-card-body">
                        <h5 class="villa-card-title">{{ $villa->baslik }}</h5>
                        <p class="villa-card-location">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $villa->region->baslik ?? '' }}
                        </p>
                        <div class="villa-price">
                            <span class="villa-price-amount">{{ number_format($villa->fiyat, 0, ',', '.') }} ₺</span>
                            <a href="{{ route('villas.show', $villa->sef) }}" class="btn btn-villa btn-sm">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">Bu kategoride henüz villa bulunmuyor.</div>
            </div>
            @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $villas->links() }}
        </div>
    </div>
</section>
@endsection
