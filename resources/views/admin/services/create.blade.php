@extends('admin.layouts.master')

@section('title', 'Yeni Hizmet - Yönetim Paneli')
@section('page-title', 'Yeni Hizmet Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Ek Hizmetler</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hizmet Bilgileri</h3>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Hizmet Adı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fiyat</label>
                        <input type="number" name="fiyat" class="form-control" value="{{ old('fiyat') }}" step="0.01" min="0" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" class="form-control" rows="3">{{ old('aciklama') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">İkon</label>
                        <input type="text" name="ikon" class="form-control" value="{{ old('ikon') }}" placeholder="fas fa-star">
                        <small class="text-muted">Font Awesome ikon sınıfı giriniz (örn: fas fa-star)</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
