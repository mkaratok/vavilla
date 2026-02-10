@extends('admin.layouts.master')

@section('title', 'Villa Tipi Düzenle - Yönetim Paneli')
@section('page-title', 'Villa Tipi Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.villa-types.index') }}">Villa Tipleri</a></li>
<li class="breadcrumb-item active">Düzenle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Villa Tipi Bilgileri</h3>
            </div>
            <form action="{{ route('admin.villa-types.update', $type) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Villa Tipi Adı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $type->baslik) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anahtar Kelimeler (SEO)</label>
                        <input type="text" name="kelime" class="form-control" value="{{ old('kelime', $type->kelime) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kısa Açıklama</label>
                        <input type="text" name="kisa" class="form-control" value="{{ old('kisa', $type->kisa) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik', $type->icerik) }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                    <a href="{{ route('admin.villa-types.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
</script>
@endpush
