@extends('admin.layouts.master')

@section('title', 'Villalar - Yönetim Paneli')
@section('page-title', 'Villalar')

@section('breadcrumb')
<li class="breadcrumb-item active">Villalar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Eklenmiş Villalar</h3>
        <div class="card-tools">
            <a href="{{ route('admin.villas.ordering') }}" class="btn btn-info btn-sm me-2">
                <i class="fas fa-sort"></i> Sıralama
            </a>
            <a href="{{ route('admin.villas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Yeni Villa Ekle
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover" id="villas-table">
            <thead>
                <tr>
                    <th width="80">Görsel</th>
                    <th>Başlık</th>
                    <th>Bölge</th>
                    <th>Tip</th>
                    <th>Fiyat</th>
                    <th>Kişi</th>
                    <th>Min. Kira</th>
                    <th>Anasayfa</th>
                    <th>Durum</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($villas as $villa)
                <tr>
                    <td>
                        @if($villa->gorsel)
                            <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="" width="60" class="rounded">
                        @else
                            <div class="bg-secondary text-white text-center rounded" style="width:60px;height:40px;line-height:40px;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $villa->baslik }}</strong>
                        <br>
                        <small class="text-muted">{{ Str::limit($villa->kisa_aciklama, 50) }}</small>
                    </td>
                    <td>{{ $villa->region->baslik ?? '-' }}</td>
                    <td>{{ $villa->villaType->baslik ?? '-' }}</td>
                    <td><strong>{{ number_format($villa->fiyat, 0, ',', '.') }} ₺</strong></td>
                    <td>{{ $villa->kisi_sayisi }} kişi</td>
                    <td>{{ $villa->minimum_kiralama_suresi }} gece</td>
                    <td>
                        @if($villa->anasayfa)
                            <span class="badge bg-info">Evet</span>
                        @else
                            <span class="badge bg-secondary">Hayır</span>
                        @endif
                    </td>
                    <td>
                        @if($villa->durum)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.villas.edit', $villa) }}" class="btn btn-warning btn-sm" title="Düzenle">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.villas.duplicate', $villa) }}" class="btn btn-info btn-sm" title="Kopyala">
                                <i class="fas fa-copy"></i>
                            </a>
                            <form action="{{ route('admin.villas.destroy', $villa) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu villayı silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Henüz villa eklenmemiş.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $villas->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#villas-table').DataTable({
        paging: false,
        info: false,
        order: [[1, 'asc']],
        language: {
            search: "Ara:",
            zeroRecords: "Sonuç bulunamadı"
        }
    });
});
</script>
@endpush
