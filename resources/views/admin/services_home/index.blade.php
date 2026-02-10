@extends('admin.layouts.master')

@section('title', 'Hizmetler (Anasayfa) - Yönetim Paneli')
@section('page-title', 'Anasayfa Hizmetleri')

@section('breadcrumb')
<li class="breadcrumb-item active">Hizmetler</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Hizmet Listesi</h3>
        <div class="card-tools">
            <a href="{{ route('admin.services-home.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Hizmet Ekle
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="80">İkon/Resim</th>
                    <th>Başlık</th>
                    <th>Sıra</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td class="text-center">
                        @if($service->resim)
                            <img src="{{ asset('storage/services/' . $service->resim) }}" class="img-fluid" style="max-height: 40px" alt="">
                        @elseif($service->ikon)
                            <i class="{{ $service->ikon }} fa-2x"></i>
                        @endif
                    </td>
                    <td>{{ $service->baslik }}</td>
                    <td>{{ $service->sira }}</td>
                    <td>
                        <a href="{{ route('admin.services-home.edit', $service->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.services-home.destroy', $service->id) }}" method="POST" class="d-inline">
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
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
