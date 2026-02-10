@extends('admin.layouts.master')

@section('title', 'Teknik Özellikler - Yönetim Paneli')
@section('page-title', 'Teknik Özellikler')

@section('breadcrumb')
<li class="breadcrumb-item active">Teknik Özellikler</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Özellik Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.features.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Özellik Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Özellik Adı</th>
                    <th>İkon</th>
                    <th>Gösterim</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($features as $feature)
                <tr>
                    <td>{{ $feature->id }}</td>
                    <td>{{ $feature->baslik }}</td>
                    <td>
                        @if($feature->ikon)
                            <i class="fa {{ $feature->ikon }} fa-lg"></i>
                            <small class="text-muted ms-2">{{ $feature->ikon }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($feature->gosterim)
                            <span class="badge bg-success">Evet</span>
                        @else
                            <span class="badge bg-secondary">Hayır</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.features.destroy', $feature) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu özelliği silmek istediğinize emin misiniz?')">
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
                    <td colspan="5" class="text-center">Henüz özellik eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $features->links() }}
        </div>
    </div>
</div>
@endsection
