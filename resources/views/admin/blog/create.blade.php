@extends('admin.layouts.master')

@section('title', 'Yeni Yazı - Yönetim Paneli')
@section('page-title', 'Yeni Yazı Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik *</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kısa Açıklama</label>
                        <textarea name="kisa" class="form-control" rows="3">{{ old('kisa') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yayın Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="durum" value="1" class="form-check-input" id="durum" checked>
                            <label class="form-check-label" for="durum">Yayında</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="anasayfa" value="1" class="form-check-input" id="anasayfa">
                            <label class="form-check-label" for="anasayfa">Anasayfada Göster</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Öne Çıkan Görsel</label>
                        <input type="file" name="resim" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Etiketler (Virgülle ayırın)</label>
                        <input type="text" name="etiket" class="form-control" value="{{ old('etiket') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keywords (SEO)</label>
                        <input type="text" name="kelime" class="form-control" value="{{ old('kelime') }}">
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
    ClassicEditor.create(document.querySelector('#editor'))
        .catch(error => console.error(error));
});
</script>
@endpush
