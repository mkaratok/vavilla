@extends('admin.layouts.master')

@section('title', 'İletişim Mesajları - Yönetim Paneli')
@section('page-title', 'İletişim Mesajları')

@section('breadcrumb')
<li class="breadcrumb-item active">İletişim Mesajları</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Gelen Kutusu</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="messages-table">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Gönderen</th>
                    <th>Konu</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th width="120">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="{{ $message->durum == 0 ? 'table-warning' : '' }}">
                    <td>{{ $message->id }}</td>
                    <td>
                        @if($message->durum == 0)
                            <i class="fas fa-envelope text-warning me-2"></i>
                        @else
                            <i class="far fa-envelope-open text-muted me-2"></i>
                        @endif
                        {{ $message->adsoyad }}
                    </td>
                    <td>{{ $message->konu }}</td>
                    <td>{{ $message->created_at?->format('d.m.Y H:i') ?? date('d.m.Y H:i', $message->tarih) }}</td>
                    <td>
                        @if($message->durum == 0)
                            <span class="badge bg-warning">Okunmadı</span>
                        @else
                            <span class="badge bg-success">Okundu</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.contact.show', $message) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.contact.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
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
                    <td>---</td>
                    <td>---</td>
                    <td>Henüz mesaj yok.</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#messages-table').DataTable({
        paging: false,
        info: false,
        order: [[0, 'desc']]
    });
});
</script>
@endpush
