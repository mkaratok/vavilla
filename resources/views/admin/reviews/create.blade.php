@extends('admin.layouts.master')

@section('title', 'Yeni Yorum - Yönetim Paneli')
@section('page-title', 'Yeni Yorum Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Yorumlar</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.reviews.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yorum Detayları</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Villa *</label>
                        <select name="villa_id" class="form-control select2" required>
                            <option value="">Seçiniz</option>
                            @foreach($villas as $villa)
                                <option value="{{ $villa->id }}" {{ old('villa_id') == $villa->id ? 'selected' : '' }}>
                                    {{ $villa->baslik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ad Soyad *</label>
                                <input type="text" name="adsoyad" class="form-control" value="{{ old('adsoyad') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Yorum *</label>
                        <textarea name="yorum" class="form-control" rows="5" required>{{ old('yorum') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Puanlama (1-10)</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Temizlik</label>
                        <input type="number" name="temizlik" class="form-control" min="1" max="10" value="{{ old('temizlik', 10) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fiyat/Performans</label>
                        <input type="number" name="ucret" class="form-control" min="1" max="10" value="{{ old('ucret', 10) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ulaşım</label>
                        <input type="number" name="ulasim" class="form-control" min="1" max="10" value="{{ old('ulasim', 10) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hizmet</label>
                        <input type="number" name="servis" class="form-control" min="1" max="10" value="{{ old('servis', 10) }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });
});
</script>
@endpush
