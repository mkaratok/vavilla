@extends('admin.layouts.master')

@section('title', 'Bölge Düzenle - Yönetim Paneli')
@section('page-title', 'Bölge Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.regions.index') }}">Bölgeler</a></li>
<li class="breadcrumb-item active">Düzenle</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bölge Bilgileri</h3>
    </div>
    <form action="{{ route('admin.regions.update', $region) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Bölge Adı *</label>
                <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $region->baslik) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Anahtar Kelimeler (SEO)</label>
                <input type="text" name="kelime" class="form-control" value="{{ old('kelime', $region->kelime) }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kısa Açıklama</label>
                <input type="text" name="kisa" class="form-control" value="{{ old('kisa', $region->kisa) }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label">İçerik</label>
                <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik', $region->icerik) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Kaydet
            </button>
            <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
</script>
@endpush
