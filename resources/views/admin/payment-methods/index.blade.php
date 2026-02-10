@extends('admin.layouts.master')

@section('title', 'Ödeme Yöntemleri - Yönetim Paneli')
@section('page-title', 'Ödeme Yöntemleri')

@section('breadcrumb')
<li class="breadcrumb-item active">Ödeme Yöntemleri</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ödeme Yöntemi Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Ödeme Yöntemi Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Ödeme Yöntemi</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($methods as $method)
                <tr>
                    <td>{{ $method->id }}</td>
                    <td>{{ $method->baslik }}</td>
                    <td>
                        <a href="{{ route('admin.payment-methods.edit', $method) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu ödeme yöntemini silmek istediğinize emin misiniz?')">
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
                    <td colspan="3" class="text-center">Henüz ödeme yöntemi eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $methods->links() }}
        </div>
    </div>
</div>
@endsection
