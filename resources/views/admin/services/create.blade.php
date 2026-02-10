@extends('admin.layouts.master')

@section('title', 'Yeni Hizmet - Yönetim Paneli')
@section('page-title', 'Yeni Hizmet Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Ek Hizmetler</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hizmet Bilgileri</h3>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Hizmet Adı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
