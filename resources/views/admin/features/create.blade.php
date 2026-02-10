@extends('admin.layouts.master')

@section('title', 'Yeni Özellik - Yönetim Paneli')
@section('page-title', 'Yeni Özellik Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.features.index') }}">Teknik Özellikler</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Özellik Bilgileri</h3>
            </div>
            <form action="{{ route('admin.features.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Özellik Adı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Font Awesome İkon</label>
                        <input type="text" name="ikon" class="form-control" value="{{ old('ikon') }}" placeholder="fa-circle-o">
                        <small class="text-muted">
                            <a href="https://fontawesomeicons.com/fa/4" target="_blank">İkon listesi</a> - Örnek: fa-wifi, fa-car, fa-swimming-pool
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="gosterim" value="1" class="form-check-input" id="gosterim"
                                {{ old('gosterim') ? 'checked' : '' }}>
                            <label class="form-check-label" for="gosterim">Villa listesinde göster (önemli özellikler için)</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                    <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
