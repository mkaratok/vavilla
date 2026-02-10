@extends('admin.layouts.master')

@section('title', 'Harici Rezervasyonlar - Yönetim Paneli')
@section('page-title', 'Harici Rezervasyonlar')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reservations.index') }}">Rezervasyonlar</a></li>
<li class="breadcrumb-item active">Harici Rezervasyonlar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Harici Kaynaktan Gelen Rezervasyonlar</h3>
        <div class="card-tools">
            <a href="{{ route('admin.reservations.create') }}?harici=1" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Harici Rezervasyon
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Booking.com, Airbnb ve diğer platformlardan gelen rezervasyonlar burada listelenir.
        </div>
        
        <table class="table table-bordered table-striped" id="reservations-table">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Villa</th>
                    <th>Müşteri</th>
                    <th>Telefon</th>
                    <th>Giriş</th>
                    <th>Çıkış</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                    <th width="120">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->villa->baslik ?? 'Silinmiş Villa' }}</td>
                    <td>{{ $reservation->adsoyad }}</td>
                    <td>{{ $reservation->telefon }}</td>
                    <td>{{ $reservation->gelis_tarihi->format('d.m.Y') }}</td>
                    <td>{{ $reservation->cikis_tarihi->format('d.m.Y') }}</td>
                    <td>{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</td>
                    <td>
                        <span class="badge bg-{{ $reservation->status_color }}">
                            {{ $reservation->status_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.reservations.edit', $reservation) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu rezervasyonu silmek istediğinize emin misiniz?')">
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
                    <td colspan="9" class="text-center">Henüz harici rezervasyon yok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#reservations-table').DataTable({
        paging: false,
        info: false,
        order: [[0, 'desc']]
    });
});
</script>
@endpush
