@extends('admin.layouts.master')

@section('title', 'Yeni Hizmet - Yönetim Paneli')
@section('page-title', 'Yeni Hizmet Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.services-home.index') }}">Hizmetler</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.services-home.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Açıklama</label>
                <textarea name="aciklama" class="form-control" rows="3">{{ old('aciklama') }}</textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">İkon (FontAwesome Class)</label>
                        <input type="text" name="ikon" class="form-control" value="{{ old('ikon') }}" placeholder="fa fa-star">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="sira" class="form-control" value="{{ old('sira', 0) }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Görsel</label>
                <input type="file" name="resim" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Kaydet
            </button>
        </div>
    </div>
</form>
@endsection
