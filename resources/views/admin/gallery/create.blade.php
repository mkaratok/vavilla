@extends('admin.layouts.master')

@section('title', 'Yeni Galeri - Yönetim Paneli')
@section('page-title', 'Yeni Galeri Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galeri</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.gallery.store') }}" method="POST">
    @csrf
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Galeri Başlığı *</label>
                <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
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
