@extends('admin.layouts.master')

@section('title', 'Hakkımızda - Yönetim Paneli')
@section('page-title', 'Hakkımızda Sayfası Düzenle')

@section('content')
<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">İçerik Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $about->baslik ?? '') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik', $about->icerik ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Görseller</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label">Görsel 1</label>
                        @if(isset($about->image_1) && $about->image_1)
                        <div class="mb-2">
                            <img src="{{ asset('storage/about/' . $about->image_1) }}" class="img-fluid rounded" alt="">
                        </div>
                        @endif
                        <input type="file" name="image_1" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Görsel 2</label>
                        @if(isset($about->image_2) && $about->image_2)
                        <div class="mb-2">
                            <img src="{{ asset('storage/about/' . $about->image_2) }}" class="img-fluid rounded" alt="">
                        </div>
                        @endif
                        <input type="file" name="image_2" class="form-control" accept="image/*">
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
