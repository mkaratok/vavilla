@extends('admin.layouts.master')

@section('title', 'Villa Tipleri - Yönetim Paneli')
@section('page-title', 'Villa Tipleri')

@section('breadcrumb')
<li class="breadcrumb-item active">Villa Tipleri</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Villa Tipi Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.villa-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Villa Tipi Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Villa Tipi</th>
                    <th>Slug</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->baslik }}</td>
                    <td><code>{{ $type->sef }}</code></td>
                    <td>
                        <a href="{{ route('admin.villa-types.edit', $type) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.villa-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu villa tipini silmek istediğinize emin misiniz?')">
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
                    <td colspan="4" class="text-center">Henüz villa tipi eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $types->links() }}
        </div>
    </div>
</div>
@endsection
