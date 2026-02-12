@extends('admin.layouts.master')

@section('title', 'Ek Hizmetler - Yönetim Paneli')
@section('page-title', 'Ek Hizmetler')

@section('breadcrumb')
<li class="breadcrumb-item active">Ek Hizmetler</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Hizmet Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Hizmet Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Hizmet Adı</th>
                    <th>Fiyat</th>
                    <th>İkon</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->baslik }}</td>
                    <td>{{ $service->fiyat ? number_format($service->fiyat, 2, ',', '.') . ' ₺' : '-' }}</td>
                    <td>
                        @if($service->ikon)
                            <i class="{{ $service->ikon }}"></i> {{ $service->ikon }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">
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
                    <td colspan="5" class="text-center">Henüz hizmet eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
