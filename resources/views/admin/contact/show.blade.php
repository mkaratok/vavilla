@extends('admin.layouts.master')

@section('title', 'Mesaj Detayı - Yönetim Paneli')
@section('page-title', 'Mesaj Detayı')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}">İletişim Mesajları</a></li>
<li class="breadcrumb-item active">Detay</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $message->konu }}</h3>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Tarih:</strong> {{ $message->created_at?->format('d.m.Y H:i') ?? date('d.m.Y H:i', $message->tarih) }}</p>
                <p><strong>IP Adresi:</strong> {{ $message->ip }}</p>
            </div>
        </div>
        
        <hr>
        
        <div class="mt-4">
            <h5>Mesaj İçeriği:</h5>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($message->mesaj)) !!}
            </div>
        </div>
        
        @if($message->dosya)
        <div class="mt-4">
            <h5>Ek Dosya:</h5>
            <a href="{{ asset('storage/' . $message->dosya) }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Dosyayı İndir
            </a>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Geri Dön
        </a>
        <form action="{{ route('admin.contact.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Sil
            </button>
        </form>
    </div>
</div>
@endsection
