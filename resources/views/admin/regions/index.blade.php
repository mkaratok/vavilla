@extends('admin.layouts.master')

@section('title', 'Bölgeler - Yönetim Paneli')
@section('page-title', 'Bölgeler')

@section('breadcrumb')
<li class="breadcrumb-item active">Bölgeler</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bölge Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.regions.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Bölge Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Bölge Adı</th>
                    <th>Villa Sayısı</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regions as $region)
                <tr>
                    <td>{{ $region->id }}</td>
                    <td>{{ $region->baslik }}</td>
                    <td><span class="badge bg-info">{{ $region->villas_count }}</span></td>
                    <td>
                        <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu bölgeyi silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Henüz bölge eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $regions->links() }}
        </div>
    </div>
</div>
@endsection
