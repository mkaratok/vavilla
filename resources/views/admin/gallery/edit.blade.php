@extends('admin.layouts.master')

@section('title', 'Galeri Düzenle - Yönetim Paneli')
@section('page-title', 'Galeri Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galeri</a></li>
<li class="breadcrumb-item active">{{ $gallery->baslik }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Galeri Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Galeri Başlığı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $gallery->baslik) }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resimler</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="file" id="gallery-upload" accept="image/*" class="form-control" multiple>
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle"></i> Birden fazla resim seçebilirsiniz.<br>
                        <i class="fas fa-exclamation-triangle text-warning"></i> Maksimum dosya boyutu: 20MB, Önerilen boyut: 4000x4000 piksel altı
                    </small>
                </div>
                
                <div class="row" id="gallery-container">
                    @foreach($gallery->images as $image)
                    <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                        <div class="card">
                            <img src="{{ asset('storage/gallery/' . $image->kresim) }}" class="card-img-top" alt="">
                            <div class="card-body p-2 text-center">
                                <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                                    <i class="fas fa-trash"></i> Sil
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Gallery upload
    $('#gallery-upload').on('change', function() {
        var files = this.files;
        if (!files.length) return;
        
        var formData = new FormData();
        
        // Append all files to formData
        for (var i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        
        // Show loading state if desired
        var btn = $(this);
        btn.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("admin.gallery.images.upload", $gallery) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                alert('Yükleme hatası: ' + (xhr.responseJSON?.message || 'Bilinmeyen hata'));
            }
        });
    });
    
    // Delete image
    $(document).on('click', '.delete-image', function() {
        if (!confirm('Bu resmi silmek istediğinize emin misiniz?')) return;
        
        var id = $(this).data('id');
        
        $.ajax({
            url: '/admin/gallery/images/' + id,
            type: 'DELETE',
            success: function(response) {
                $('#image-' + id).fadeOut(300, function() { $(this).remove(); });
            },
            error: function(xhr) {
                alert('Silme hatası');
            }
        });
    });
});
</script>
@endpush
