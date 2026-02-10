@extends('admin.layouts.master')

@section('title', 'Yeni Rezervasyon - Yönetim Paneli')
@section('page-title', 'Yeni Rezervasyon Ekle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.reservations.index') }}">Rezervasyonlar</a></li>
<li class="breadcrumb-item active">Yeni Ekle</li>
@endsection

@section('content')
<form action="{{ route('admin.reservations.store') }}" method="POST" id="reservation-form">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rezervasyon Bilgileri</h3>
                </div>
                <div class="card-body">
                    <!-- Villa Seçimi -->
                    <div class="mb-3">
                        <label class="form-label">Villa *</label>
                        <select name="villa_id" id="villa_id" class="form-control select2" required>
                            <option value="">Villa Seçiniz</option>
                            @foreach($villas as $villa)
                                <option value="{{ $villa->id }}" 
                                    data-price="{{ $villa->fiyat }}"
                                    data-min-days="{{ $villa->minimum_kiralama_suresi }}"
                                    {{ (old('villa_id') ?? $selectedVilla?->id) == $villa->id ? 'selected' : '' }}>
                                    {{ $villa->baslik }} - {{ number_format($villa->fiyat, 0, ',', '.') }} ₺/gece
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Tarih Seçimi -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Giriş Tarihi *</label>
                                <input type="date" name="gelis_tarihi" id="gelis_tarihi" class="form-control" 
                                    value="{{ old('gelis_tarihi') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Çıkış Tarihi *</label>
                                <input type="date" name="cikis_tarihi" id="cikis_tarihi" class="form-control" 
                                    value="{{ old('cikis_tarihi') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info" id="price-info" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Gece Sayısı:</strong> <span id="night-count">0</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Hesaplanan Tutar:</strong> <span id="calculated-price">0 ₺</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Müşteri Bilgileri -->
                    <h5 class="mb-3">Müşteri Bilgileri</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ad Soyad *</label>
                                <input type="text" name="adsoyad" class="form-control" value="{{ old('adsoyad') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">TC Kimlik No</label>
                                <input type="text" name="tc" class="form-control" value="{{ old('tc') }}" maxlength="11">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telefon *</label>
                                <input type="text" name="telefon" class="form-control" value="{{ old('telefon') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" name="adres" class="form-control" value="{{ old('adres') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Yetişkin Sayısı</label>
                                <input type="number" name="yetiskin" class="form-control" value="{{ old('yetiskin', 2) }}" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Çocuk Sayısı</label>
                                <input type="number" name="cocuk" class="form-control" value="{{ old('cocuk', 0) }}" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Müşteri Notu</label>
                        <textarea name="notu" class="form-control" rows="3">{{ old('notu') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Ek Hizmetler -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ek Hizmetler</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($services as $service)
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="ek_hizmetler[]" value="{{ $service->id }}" 
                                    class="form-check-input" id="service_{{ $service->id }}"
                                    {{ in_array($service->id, old('ek_hizmetler', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="service_{{ $service->id }}">
                                    {{ $service->baslik }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Yan Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ödeme Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Toplam Tutar (₺) *</label>
                        <input type="number" name="toplam_tutar" id="toplam_tutar" class="form-control" 
                            value="{{ old('toplam_tutar', 0) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ödeme Yöntemi</label>
                        <select name="odeme_tipi" class="form-control">
                            <option value="">Seçiniz</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->baslik }}" {{ old('odeme_tipi') == $method->baslik ? 'selected' : '' }}>
                                    {{ $method->baslik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Durum</label>
                        <select name="durum" class="form-control">
                            <option value="0" {{ old('durum') == '0' ? 'selected' : '' }}>Beklemede</option>
                            <option value="1" {{ old('durum') == '1' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="2" {{ old('durum') == '2' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="3" {{ old('durum') == '3' ? 'selected' : '' }}>İptal</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="harici" value="1" class="form-check-input" id="harici"
                                {{ old('harici') ? 'checked' : '' }}>
                            <label class="form-check-label" for="harici">Harici Rezervasyon</label>
                        </div>
                        <small class="text-muted">Booking.com, Airbnb vb. kaynaklı rezervasyonlar</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Notu</label>
                        <textarea name="admin_notu" class="form-control" rows="3">{{ old('admin_notu') }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-save me-2"></i>Rezervasyon Ekle
                    </button>
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
    
    var occupiedDates = [];
    
    // Fetch occupied dates when villa is selected
    function fetchOccupiedDates() {
        var villaId = $('#villa_id').val();
        if (!villaId) {
            occupiedDates = [];
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.reservations.get-occupied-dates") }}',
            type: 'POST',
            data: {
                villa_id: villaId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                occupiedDates = response.occupied || [];
                updateDatePickerConstraints();
            }
        });
    }
    
    // Update date picker constraints
    function updateDatePickerConstraints() {
        var gelis = $('#gelis_tarihi').val();
        var cikis = $('#cikis_tarihi').val();
        
        // Check if selected dates are occupied
        if (gelis && isDateOccupied(gelis)) {
            alert('Seçilen giriş tarihi dolu! Lütfen başka bir tarih seçin.');
            $('#gelis_tarihi').val('');
        }
        if (cikis && isDateOccupied(cikis)) {
            alert('Seçilen çıkış tarihi dolu! Lütfen başka bir tarih seçin.');
            $('#cikis_tarihi').val('');
        }
    }
    
    // Check if a date is occupied
    function isDateOccupied(dateStr) {
        var date = new Date(dateStr);
        var formatted = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        return occupiedDates.includes(formatted);
    }
    
    // Calculate price when dates change
    function calculatePrice() {
        var villaId = $('#villa_id').val();
        var gelis = $('#gelis_tarihi').val();
        var cikis = $('#cikis_tarihi').val();
        
        if (!villaId || !gelis || !cikis) {
            $('#price-info').hide();
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.reservations.calculate-price") }}',
            type: 'POST',
            data: {
                villa_id: villaId,
                gelis_tarihi: gelis,
                cikis_tarihi: cikis,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#night-count').text(response.nights);
                $('#calculated-price').text(response.formatted);
                $('#toplam_tutar').val(response.total);
                $('#price-info').show();
            }
        });
    }
    
    $('#villa_id').on('change', function() {
        fetchOccupiedDates();
        calculatePrice();
    });
    
    $('#gelis_tarihi, #cikis_tarihi').on('change', function() {
        updateDatePickerConstraints();
        calculatePrice();
    });
    
    // Set minimum checkout date
    $('#gelis_tarihi').on('change', function() {
        var gelis = new Date($(this).val());
        var minDays = $('#villa_id option:selected').data('min-days') || 1;
        gelis.setDate(gelis.getDate() + minDays);
        $('#cikis_tarihi').attr('min', gelis.toISOString().split('T')[0]);
    });
    
    // Initial calculation if villa is preselected
    if ($('#villa_id').val()) {
        fetchOccupiedDates();
        calculatePrice();
    }
});
</script>
@endpush
