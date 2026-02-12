@extends('admin.layouts.master')

@section('title', 'Yeni Villa - Yönetim Paneli')
@section('page-title', 'Yeni Villa Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.villas.index') }}">Villalar</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.villas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
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
                        <input type="text" name="baslik" class="form-control" value="{{ old('baslik') }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bölge</label>
                                <select name="region_id" class="form-control select2">
                                    <option value="">Seçiniz</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->baslik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Yetişkin Kapasitesi</label>
                                <input type="number" name="yetiskin" class="form-control" value="{{ old('yetiskin', 4) }}" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fiyat (₺) *</label>
                                <input type="number" name="fiyat" class="form-control" value="{{ old('fiyat', 0) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Eski Fiyat (₺)</label>
                                <input type="number" name="eski_fiyat" class="form-control" value="{{ old('eski_fiyat', 0) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Min. Kiralama Süresi (Gece)</label>
                                <input type="number" name="minimum_kiralama_suresi" class="form-control" value="{{ old('minimum_kiralama_suresi', 1) }}" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kısa Açıklama</label>
                        <input type="text" name="kisa" class="form-control" value="{{ old('kisa') }}" maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Anahtar Kelimeler (SEO)</label>
                        <input type="text" name="kelime" class="form-control" value="{{ old('kelime') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" name="adres" class="form-control" value="{{ old('adres') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="icerik" id="editor" class="form-control" rows="10">{{ old('icerik') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konum (Google Maps Embed Kodu)</label>
                        <textarea name="konum" class="form-control" rows="3" placeholder="<iframe src='...'></iframe>">{{ old('konum') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Teknik Özellikler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Teknik Özellikler</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($features as $feature)
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="teknik_ozellikler[]" value="{{ $feature->id }}" 
                                    class="form-check-input" id="feature_{{ $feature->id }}"
                                    {{ in_array($feature->id, old('teknik_ozellikler', [])) ? 'checked' : '' }}>
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
                    @if($services->count() > 0)
                        <div class="row">
                            @foreach($services as $service)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 {{ in_array($service->id, old('ek_hizmetler', [])) ? 'bg-light border-primary' : '' }}">
                                    <div class="form-check">
                                        <input type="checkbox" name="ek_hizmetler[]" value="{{ $service->id }}" 
                                            class="form-check-input" id="service_{{ $service->id }}"
                                            {{ in_array($service->id, old('ek_hizmetler', [])) ? 'checked' : '' }}>
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
                                {{ old('durum', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="durum">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="anasayfa" value="1" class="form-check-input" id="anasayfa"
                                {{ old('anasayfa') ? 'checked' : '' }}>
                            <label class="form-check-label" for="anasayfa">Anasayfada Göster</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="firsat" value="1" class="form-check-input" id="firsat"
                                {{ old('firsat') ? 'checked' : '' }}>
                            <label class="form-check-label" for="firsat">Fırsat Villası</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </div>
            
            <!-- Ana Görsel -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ana Görsel</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="file" name="gorsel" class="form-control" accept="image/*">
                        <small class="text-muted">Max: 2MB, JPG/PNG</small>
                    </div>
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
});
</script>
@endpush
