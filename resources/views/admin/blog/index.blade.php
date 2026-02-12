@extends('admin.layouts.master')

@section('title', 'Blog - Yönetim Paneli')
@section('page-title', 'Blog Yazıları')

@section('breadcrumb')
<li class="breadcrumb-item active">Blog</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Yazı Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Yazı Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="80">Görsel</th>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td class="text-center">
                        @if($blog->resim)
                            <img src="{{ asset('storage/blog/' . $blog->resim) }}" class="img-fluid" style="max-height: 40px" alt="">
                        @endif
                    </td>
                    <td>{{ $blog->baslik }}</td>
                    <td>{{ $blog->kategori }}</td>
                    <td>{{ $blog->tarih ?? '-' }}</td>
                    <td>
                        @if($blog->durum)
                            <span class="badge bg-success">Yayında</span>
                        @else
                            <span class="badge bg-secondary">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="d-inline">
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
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection
