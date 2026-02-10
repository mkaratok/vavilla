@extends('public.layouts.master')

@section('title', $region->baslik . ' Villaları - ' . ($settings->siteadi ?? 'Villa Kiralama'))
@section('description', $region->kisa ?? '')
@section('keywords', $region->kelime ?? '')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header-title">{{ $region->baslik }} Villaları</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('villas.index') }}">Villalar</a></li>
                <li class="breadcrumb-item active">{{ $region->baslik }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($region->icerik)
        <div class="mb-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    {!! $region->icerik !!}
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
                        <div class="villa-features">
                            <span class="villa-feature"><i class="fas fa-users"></i> {{ $villa->yetiskin }} Kişi</span>
                        </div>
                        <div class="villa-price">
                            <span class="villa-price-amount">{{ number_format($villa->fiyat, 0, ',', '.') }} ₺</span>
                            <a href="{{ route('villas.show', $villa->sef) }}" class="btn btn-villa btn-sm">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">Bu bölgede henüz villa bulunmuyor.</div>
            </div>
            @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $villas->links() }}
        </div>
    </div>
</section>
@endsection
