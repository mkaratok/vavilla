@extends('admin.layouts.master')

@section('title', 'Galeri - Yönetim Paneli')
@section('page-title', 'Foto Galeri Yönetimi')

@section('breadcrumb')
<li class="breadcrumb-item active">Galeri</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Galeriler</h3>
        <div class="card-tools">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Galeri Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Başlık</th>
                    <th>Resim Sayısı</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($galleries as $gallery)
                <tr>
                    <td>{{ $gallery->id }}</td>
                    <td>{{ $gallery->baslik }}</td>
                    <td>{{ $gallery->images_count }}</td>
                    <td>
                        <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $galleries->links() }}
        </div>
    </div>
</div>
@endsection
