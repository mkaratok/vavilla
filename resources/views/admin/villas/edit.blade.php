@extends('admin.layouts.master')

@section('title', 'Villa Düzenle - Yönetim Paneli')
@section('page-title', 'Villa Düzenle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.villas.index') }}">Villalar</a></li>
<li class="breadcrumb-item active">{{ $villa->baslik }}</li>
@endsection

@section('content')
<form action="{{ route('admin.villas.update', $villa) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Ana Bilgiler -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Villa Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Villa Adı *</label>
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik', $villa->baslik) }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bölge</label>
                                <select name="region_id" class="form-control select2">
                                    <option value="">Seçiniz</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $villa->region_id) == $region->id ? 'selected' : '' }}>
                                            {{ $region->baslik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Yetişkin Kapasitesi</label>
                                <input type="number" name="yetiskin" class="form-control" value="{{ old('yetiskin', $villa->yetiskin) }}" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fiyat (₺) *</label>
                                <input type="number" name="fiyat" class="form-control" value="{{ old('fiyat', $villa->fiyat) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Eski Fiyat (₺)</label>
                                <input type="number" name="eski_fiyat" class="form-control" value="{{ old('eski_fiyat', $villa->eski_fiyat) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Min. Kiralama Süresi (Gece)</label>
                                <input type="number" name="minimum_kiralama_suresi" class="form-control" value="{{ old('minimum_kiralama_suresi', $villa->minimum_kiralama_suresi) }}" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kısa Açıklama</label>
                        <input type="text" name="kisa" class="form-control" value="{{ old('kisa', $villa->kisa) }}" maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anahtar Kelimeler (SEO)</label>
                        <input type="text" name="kelime" class="form-control" value="{{ old('kelime', $villa->kelime) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" name="adres" class="form-control" value="{{ old('adres', $villa->adres) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik', $villa->icerik) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konum (Google Maps Embed Kodu)</label>
                        <textarea name="konum" class="form-control" rows="3">{{ old('konum', $villa->konum) }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Teknik Özellikler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Teknik Özellikler</h3>
                </div>
                <div class="card-body">
                    @php
                        $selectedFeatures = old('teknik_ozellikler', $villa->teknik_ozellikler ?? []);
                        if (!is_array($selectedFeatures)) $selectedFeatures = [];
                    @endphp
                    <div class="row">
                        @foreach($features as $feature)
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="teknik_ozellikler[]" value="{{ $feature->id }}" 
                                    class="form-check-input" id="feature_{{ $feature->id }}"
                                    {{ in_array($feature->id, $selectedFeatures) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_{{ $feature->id }}">
                                    @if($feature->ikon)
                                        <i class="fa {{ $feature->ikon }} me-1"></i>
                                    @endif
                                    {{ $feature->baslik }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Ek Hizmetler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ek Hizmetler</h3>
                </div>
                <div class="card-body">
                    @php
                        $selectedServices = old('ek_hizmetler', $villa->ek_hizmetler ?? []);
                        if (!is_array($selectedServices)) $selectedServices = [];
                    @endphp
                    @if($services->count() > 0)
                        <div class="row">
                            @foreach($services as $service)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 {{ in_array($service->id, $selectedServices) ? 'bg-light border-primary' : '' }}">
                                    <div class="form-check">
                                        <input type="checkbox" name="ek_hizmetler[]" value="{{ $service->id }}" 
                                            class="form-check-input" id="service_{{ $service->id }}"
                                            {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="service_{{ $service->id }}">
                                            <div class="d-flex align-items-start gap-2">
                                                @if($service->ikon)
                                                    <i class="{{ $service->ikon }} text-primary" style="font-size: 1.5rem; min-width: 30px;"></i>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <strong>{{ $service->baslik }}</strong>
                                                    @if($service->fiyat)
                                                        <span class="badge bg-warning text-dark ms-2">{{ number_format($service->fiyat, 0, ',', '.') }} ₺</span>
                                                    @endif
                                                    @if($service->aciklama)
                                                        <div class="text-muted small mt-1">{{ Str::limit($service->aciklama, 80) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Henüz ek hizmet eklenmemiş. <a href="{{ route('admin.services.create') }}">Yeni hizmet ekleyin</a></p>
                    @endif
                </div>
            </div>
            
            <!-- Galeri -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Galeri Resimleri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="file" id="gallery-upload" accept="image/*" class="form-control">
                        <small class="text-muted">Resim seçin ve yükleyin. Max: 5MB</small>
                    </div>
                    
                    <div class="row" id="gallery-container">
                        @foreach($images as $image)
                        <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                            <div class="card">
                                <img src="{{ asset('storage/villas/' . $image->kresim) }}" class="card-img-top" alt="">
                                <div class="card-body p-2 text-center">
                                    <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                                        <i class="fas fa-trash"></i> Sil
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Sezonluk Fiyatlar -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sezonluk Fiyatlar</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Başlangıç</th>
                                <th>Bitiş</th>
                                <th>Gecelik Fiyat</th>
                                <th width="80">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $price)
                            <tr>
                                <td>{{ $price->tarih1->format('d.m.Y') }}</td>
                                <td>{{ $price->tarih2->format('d.m.Y') }}</td>
                                <td>{{ number_format($price->fiyat, 0, ',', '.') }} ₺</td>
                                <td>
                                    <form action="{{ route('admin.villas.prices.delete', $price) }}" method="POST" class="d-inline">
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
                    
                    <hr>
                    <h6>Yeni Fiyat Dönemi Ekle</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="date" name="price_tarih1" id="price_tarih1" class="form-control" placeholder="Başlangıç">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="price_tarih2" id="price_tarih2" class="form-control" placeholder="Bitiş">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="price_fiyat" id="price_fiyat" class="form-control" placeholder="Fiyat (₺)">
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="add-price" class="btn btn-success">
                                <i class="fas fa-plus"></i> Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Yan Panel -->
        <div class="col-md-4">
            <!-- Yayın Durumu -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yayın Durumu</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="durum" value="1" class="form-check-input" id="durum" 
                                {{ old('durum', $villa->durum) ? 'checked' : '' }}>
                            <label class="form-check-label" for="durum">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="anasayfa" value="1" class="form-check-input" id="anasayfa"
                                {{ old('anasayfa', $villa->anasayfa) ? 'checked' : '' }}>
                            <label class="form-check-label" for="anasayfa">Anasayfada Göster</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="firsat" value="1" class="form-check-input" id="firsat"
                                {{ old('firsat', $villa->firsat) ? 'checked' : '' }}>
                            <label class="form-check-label" for="firsat">Fırsat Villası</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Güncelle
                    </button>
                </div>
            </div>
            
            <!-- Ana Görsel -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ana Görsel</h3>
                </div>
                <div class="card-body">
                    @if($villa->gorsel)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" class="img-fluid rounded" alt="">
                    </div>
                    @endif
                    <div class="mb-3">
                        <input type="file" name="gorsel" class="form-control" accept="image/*">
                        <small class="text-muted">Yeni görsel yüklemek için seçin</small>
                    </div>
                </div>
            </div>
            
            <!-- İstatistikler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">İstatistikler</h3>
                </div>
                <div class="card-body">
                    <p><strong>Toplam Rezervasyon:</strong> {{ $villa->reservations->count() }}</p>
                    <p><strong>Galeri Resmi:</strong> {{ $images->count() }}</p>
                    <p><strong>Fiyat Dönemi:</strong> {{ $prices->count() }}</p>
                    <p><strong>Slug:</strong> <code>{{ $villa->sef }}</code></p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    ClassicEditor.create(document.querySelector('#editor'))
        .catch(error => console.error(error));
    
    // Gallery upload
    $('#gallery-upload').on('change', function() {
        var file = this.files[0];
        if (!file) return;
        
        var formData = new FormData();
        formData.append('image', file);
        
        $.ajax({
            url: '{{ route("admin.villas.images.upload", $villa) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Yükleme hatası: ' + xhr.responseJSON?.message || 'Bilinmeyen hata');
            }
        });
    });
    
    // Delete image
    $(document).on('click', '.delete-image', function() {
        if (!confirm('Bu resmi silmek istediğinize emin misiniz?')) return;
        
        var id = $(this).data('id');
        
        $.ajax({
            url: '/admin/villas/images/' + id,
            type: 'DELETE',
            success: function(response) {
                $('#image-' + id).fadeOut(300, function() { $(this).remove(); });
            },
            error: function(xhr) {
                alert('Silme hatası');
            }
        });
    });
    
    // Add seasonal price
    $('#add-price').on('click', function() {
        var tarih1 = $('#price_tarih1').val();
        var tarih2 = $('#price_tarih2').val();
        var fiyat = $('#price_fiyat').val();
        
        if (!tarih1 || !tarih2 || !fiyat) {
            alert('Tüm alanları doldurun');
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.villas.prices.store", $villa) }}',
            type: 'POST',
            data: {
                tarih1: tarih1,
                tarih2: tarih2,
                fiyat: fiyat
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Ekleme hatası');
            }
        });
    });
});
</script>
@endpush
