@extends('admin.layouts.master')

@section('title', 'Anasayfa Sıralama - Yönetim Paneli')
@section('page-title', 'Anasayfa Villa Sıralaması')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.villas.index') }}">Villalar</a></li>
<li class="breadcrumb-item active">Anasayfa Sıralama</li>
@endsection

@push('styles')
<style>
.sortable-item {
    cursor: move;
    padding: 15px;
    margin-bottom: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    display: flex;
    align-items: center;
}
.sortable-item:hover {
    background: #f8f9fa;
}
.sortable-item img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 15px;
}
.sortable-item .handle {
    margin-right: 15px;
    color: #999;
}
.sortable-ghost {
    opacity: 0.4;
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sıralamayı Değiştir</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="save-order">
                <i class="fas fa-save"></i> Sıralamayı Kaydet
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($villas->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Anasayfada gösterilecek villa yok. Villa düzenleme sayfasından "Anasayfada Göster" seçeneğini işaretleyin.
        </div>
        @else
        <p class="text-muted mb-3">
            <i class="fas fa-info-circle me-1"></i>
            Villaları sürükleyerek sıralamayı değiştirebilirsiniz.
        </p>
        
        <div id="sortable-list">
            @foreach($villas as $villa)
            <div class="sortable-item" data-id="{{ $villa->id }}">
                <span class="handle"><i class="fas fa-grip-vertical fa-lg"></i></span>
                @if($villa->gorsel)
                    <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="">
                @else
                    <div style="width:80px;height:60px;background:#eee;border-radius:4px;margin-right:15px;"></div>
                @endif
                <div>
                    <strong>{{ $villa->baslik }}</strong>
                    <br>
                    <small class="text-muted">
                        {{ $villa->region->baslik ?? '-' }} | 
                        {{ number_format($villa->fiyat, 0, ',', '.') }} ₺
                    </small>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    var sortable = new Sortable(document.getElementById('sortable-list'), {
        handle: '.handle',
        animation: 150,
        ghostClass: 'sortable-ghost'
    });
    
    $('#save-order').on('click', function() {
        var order = [];
        $('#sortable-list .sortable-item').each(function() {
            order.push($(this).data('id'));
        });
        
        $.ajax({
            url: '{{ route("admin.villas.ordering.update") }}',
            type: 'POST',
            data: { order: order },
            success: function(response) {
                alert('Sıralama kaydedildi!');
            },
            error: function(xhr) {
                alert('Kaydetme hatası');
            }
        });
    });
});
</script>
@endpush
