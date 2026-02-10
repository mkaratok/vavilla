@extends('admin.layouts.master')

@section('title', 'Hizmet Düzenle - Yönetim Paneli')
@section('page-title', 'Hizmet Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.services-home.index') }}">Hizmetler</a></li>
<li class="breadcrumb-item active">Düzenle</li>
@endsection

@section('content')
<form action="{{ route('admin.services-home.update', $service->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Başlık *</label>
                <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $service->baslik) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Açıklama</label>
                <textarea name="aciklama" class="form-control" rows="3">{{ old('aciklama', $service->aciklama) }}</textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">İkon (FontAwesome Class)</label>
                        <input type="text" name="ikon" class="form-control" value="{{ old('ikon', $service->ikon) }}" placeholder="fa fa-star">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Sıra</label>
                        <input type="number" name="sira" class="form-control" value="{{ old('sira', $service->sira) }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mevcut Görsel</label>
                @if($service->resim)
                    <div class="mb-2">
                        <img src="{{ asset('storage/services/' . $service->resim) }}" class="img-fluid" style="max-height: 100px" alt="">
                    </div>
                @endif
                <label class="form-label">Yeni Görsel Yükle</label>
                <input type="file" name="resim" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Güncelle
            </button>
        </div>
    </div>
</form>
@endsection
