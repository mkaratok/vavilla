@extends('admin.layouts.master')

@section('title', 'Yorumlar - Yönetim Paneli')
@section('page-title', 'Misafir Yorumları')

@section('breadcrumb')
<li class="breadcrumb-item active">Yorumlar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Yorum Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Yorum Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Villa</th>
                    <th>Misafir</th>
                    <th>Ortalama</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->villa->baslik ?? '-' }}</td>
                    <td>
                        {{ $review->adsoyad }}
                        <small class="d-block text-muted">{{ $review->email }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ number_format($review->ortalama, 1) }}</span>
                    </td>
                    <td>
                        {{ $review->tarih ? $review->tarih->format('d.m.Y') : '-' }}
                    </td>
                    <td>
                        @if($review->durum)
                            <span class="badge bg-success">Onaylı</span>
                        @else
                            <span class="badge bg-warning">Bekliyor</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
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
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
