@extends('admin.layouts.master')

@section('title', 'Yazı Düzenle - Yönetim Paneli')
@section('page-title', 'Yazı Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
<li class="breadcrumb-item active">Düzenle</li>
@endsection

@section('content')
<form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Başlık *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $blog->baslik) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik *</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik', $blog->icerik) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kısa Açıklama</label>
                        <textarea name="kisa" class="form-control" rows="3">{{ old('kisa', $blog->kisa) }}</textarea>
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
                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $blog->kategori) }}">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="durum" value="1" class="form-check-input" id="durum" {{ $blog->durum ? 'checked' : '' }}>
                            <label class="form-check-label" for="durum">Yayında</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="anasayfa" value="1" class="form-check-input" id="anasayfa" {{ $blog->anasayfa ? 'checked' : '' }}>
                            <label class="form-check-label" for="anasayfa">Anasayfada Göster</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Öne Çıkan Görsel</label>
                        @if($blog->resim)
                            <div class="mb-2">
                                <img src="{{ asset('storage/blog/' . $blog->resim) }}" class="img-fluid rounded" alt="">
                            </div>
                        @endif
                        <input type="file" name="resim" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Etiketler (Virgülle ayırın)</label>
                        <input type="text" name="etiket" class="form-control" value="{{ old('etiket', $blog->etiket) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keywords (SEO)</label>
                        <input type="text" name="kelime" class="form-control" value="{{ old('kelime', $blog->kelime) }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <p><strong>Okunma:</strong> {{ $blog->okunma }}</p>
                    <p><strong>Tarih:</strong> {{ $blog->tarih ? $blog->tarih->format('d.m.Y') : '' }}</p>
                    <p><strong>Slug:</strong> <code>{{ $blog->sef }}</code></p>
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
