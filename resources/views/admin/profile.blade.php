@extends('admin.layouts.master')

@section('title', 'Bilgilerim - Yönetim Paneli')
@section('page-title', 'Bilgilerim')

@section('breadcrumb')
<li class="breadcrumb-item active">Bilgilerim</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profil Bilgileri</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="adsoyad" class="form-control" value="{{ auth('admin')->user()->adsoyad }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="{{ auth('admin')->user()->email }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="tel" class="form-control" value="{{ auth('admin')->user()->tel }}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Şifre Değiştir</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Mevcut Şifre</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Yeni Şifre</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Yeni Şifre (Tekrar)</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-warning">Şifreyi Değiştir</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Son Giriş Bilgileri</h3>
            </div>
            <div class="card-body">
                <p><strong>Son Giriş:</strong> {{ auth('admin')->user()->songiris ?? 'Yok' }}</p>
                <p><strong>IP Adresi:</strong> {{ auth('admin')->user()->songirisip ?? 'Yok' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
