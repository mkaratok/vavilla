@extends('public.layouts.master')

@section('title', 'Rezervasyon - ' . $villa->baslik)

@push('styles')
<style>
/* Sticky Sidebar Styles */
.sidebar-sticky-wrapper {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    z-index: 90;
    align-self: flex-start;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

/* Custom Scrollbar for Sidebar */
.sidebar-sticky-wrapper::-webkit-scrollbar {
    width: 6px;
}

.sidebar-sticky-wrapper::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
}

.sidebar-sticky-wrapper::-webkit-scrollbar-thumb {
    background: rgba(197, 164, 126, 0.3);
    border-radius: 10px;
}

.sidebar-sticky-wrapper::-webkit-scrollbar-thumb:hover {
    background: rgba(197, 164, 126, 0.5);
}

.reservation-summary {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    padding: 2rem;
    color: #fff;
    border-radius: 20px;
    width: 100%;
}

/* Ensure the row allows sticky to work */
.reservation-row {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}
.villa-mini-card {
    display: flex;
    gap: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 1rem;
}
.villa-mini-card img {
    width: 100px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}
.form-control::placeholder { color: rgba(255,255,255,0.3) !important; }
.form-check-input:checked { background-color: var(--gold-accent); border-color: var(--gold-accent); }
/* Flatpickr Dark Theme Overrides */
.flatpickr-calendar { background: #1a1a1a !important; border: 1px solid rgba(197, 164, 126, 0.2) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; z-index: 9999 !important; }
.flatpickr-day { color: rgba(255,255,255,0.7) !important; font-size: 0.9rem !important; }
.flatpickr-day:hover { background: #333 !important; color: #fff !important; }
.flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: var(--gold-accent) !important; border-color: var(--gold-accent) !important; color: #000 !important; }
.flatpickr-day.disabled { color: #555 !important; background: transparent !important; cursor: not-allowed !important; opacity: 0.5 !important; }
.flatpickr-day.disabled:hover { background: transparent !important; }
.flatpickr-day.inRange { background: rgba(197, 164, 126, 0.2) !important; color: #fff !important; }
.flatpickr-day.today { border-color: var(--gold-accent) !important; }

/* Header & Text Colors */
.flatpickr-months .flatpickr-month { background: #1a1a1a !important; color: #fff !important; fill: #fff !important; border-bottom: 1px solid rgba(197, 164, 126, 0.1) !important; }
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year { color: #fff !important; font-weight: 600; }
.flatpickr-current-month .flatpickr-monthDropdown-months:hover,
.flatpickr-current-month input.cur-year:hover { background: rgba(197, 164, 126, 0.1); }
.flatpickr-weekdays { background: #1a1a1a !important; }
span.flatpickr-weekday { color: rgba(197, 164, 126, 0.6) !important; font-size: 0.75rem !important; }

/* Arrows */
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month { color: rgba(197, 164, 126, 0.8) !important; fill: rgba(197, 164, 126, 0.8) !important; }
.flatpickr-months .flatpickr-prev-month:hover svg, 
.flatpickr-months .flatpickr-next-month:hover svg { fill: var(--gold-accent) !important; }

/* Breadcrumb Overrides */
.breadcrumb-custom .breadcrumb-item a { color: rgba(255,255,255,0.7); text-decoration: none; }
.breadcrumb-custom .breadcrumb-item a:hover { color: var(--gold-accent); }
.breadcrumb-custom .breadcrumb-item.active { color: #fff; }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.4); }
</style>
@endpush

@section('content')
<div class="page-header mt-5 pt-5">
    <div class="container mt-5">
        <h1 class="page-header-title">Rezervasyon Yap</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('villas.show', $villa->sef) }}">{{ $villa->baslik }}</a></li>
                <li class="breadcrumb-item active">Rezervasyon</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <form action="{{ route('reservation.store', $villa->sef) }}" method="POST" id="reservation-form">
            @csrf
            <div class="row reservation-row">
                <div class="col-lg-8">
                    <!-- Tarih Seçimi -->
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-calendar-alt text-warning me-2"></i>Tarih Seçimi</h5>
                            
                            <!-- Takvim Görünümü -->
                            <div id="calendar-container" class="mb-4" style="display: none;">
                                <div class="calendar-wrapper" style="background: #0c0c0c; border: 1px solid rgba(197, 164, 126, 0.2); border-radius: 10px; padding: 1rem;">
                                    <div id="calendar-months">
                                        <!-- Takvim burada gösterilecek -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Giriş Tarihi *</label>
                                    <input type="text" name="gelis_tarihi" id="gelis_tarihi" class="form-control bg-dark border-secondary text-white" 
                                        value="{{ old('gelis_tarihi', $gelis) }}" required placeholder="Tarih seçin" style="cursor: pointer;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Çıkış Tarihi *</label>
                                    <input type="text" name="cikis_tarihi" id="cikis_tarihi" class="form-control bg-dark border-secondary text-white" 
                                        value="{{ old('cikis_tarihi', $cikis) }}" required placeholder="Tarih seçin" style="cursor: pointer;">
                                </div>
                            </div>
                            
                            <!-- Doluluk Durumu -->
                            <div class="availability-status mb-3 p-3 rounded" style="background-color: #0c0c0c; border: 1px solid rgba(255,255,255,0.1);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-white-50 small">Doluluk Durumu:</span>
                                        <div class="mt-2" id="availability-message">
                                            <span class="text-white-50">Tarih seçim yapınız</span>
                                        </div>
                                    </div>
                                    <div>
                                        <small class="text-white-50 d-block mb-2">Gösterge:</small>
                                        <div class="d-flex gap-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <div style="width: 12px; height: 12px; background: #28a745; border-radius: 2px;"></div>
                                                <small class="text-white-50">Müsait</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <div style="width: 12px; height: 12px; background: #dc3545; border-radius: 2px;"></div>
                                                <small class="text-white-50">Dolu</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-dark border-start border-warning border-4 text-white-50" id="price-info" style="display: none; background-color: #222;">
                                <div class="row align-items-center">
                                    <div class="col-md-4"><strong>Gece:</strong> <span id="night-count" class="text-white">0</span></div>
                                    <div class="col-md-4"><strong>Toplam:</strong> <span id="total-price" class="text-warning">0 ₺</span></div>
                                    <div class="col-md-4"><strong>Ön Ödeme:</strong> <span id="prepayment" class="text-white">0 ₺</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kişi Bilgileri -->
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-user text-warning me-2"></i>Kişisel Bilgiler</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Ad Soyad *</label>
                                    <input type="text" name="adsoyad" class="form-control bg-dark border-secondary text-white" value="{{ old('adsoyad') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">TC Kimlik No</label>
                                    <input type="text" name="tc" class="form-control bg-dark border-secondary text-white" 
                                        value="{{ old('tc') }}" maxlength="11" pattern="\d{11}" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                                        title="TC Kimlik Numarası 11 haneli olmalıdır ve sadece rakamlardan oluşmalıdır.">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Telefon *</label>
                                    <input type="tel" name="telefon" class="form-control bg-dark border-secondary text-white" 
                                        value="{{ old('telefon') }}" required placeholder="05XX XXX XX XX"
                                        oninput="this.value = this.value.replace(/[^\d+]/g, '');">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">E-posta *</label>
                                    <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-white-50">Adres</label>
                                    <input type="text" name="adres" class="form-control bg-dark border-secondary text-white" value="{{ old('adres') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Misafir Bilgileri -->
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-users text-warning me-2"></i>Misafir Bilgileri</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Yetişkin Sayısı *</label>
                                    <select name="yetiskin" class="form-select bg-dark border-secondary text-white" required>
                                        @for($i = 1; $i <= $villa->yetiskin; $i++)
                                            <option value="{{ $i }}" {{ old('yetiskin', request('yetiskin', 1)) == $i ? 'selected' : '' }}>{{ $i }} Yetişkin</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Çocuk Sayısı</label>
                                    <select name="cocuk" class="form-select bg-dark border-secondary text-white">
                                        @for($i = 0; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('cocuk', request('cocuk', 0)) == $i ? 'selected' : '' }}>{{ $i }} Çocuk</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ek Hizmetler -->
                    @if($services->count() > 0)
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-concierge-bell text-warning me-2"></i>Ek Hizmetler</h5>
                            <div class="row">
                                @foreach($services as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check p-3 bg-dark bg-opacity-50 rounded">
                                        <div class="d-flex align-items-start gap-3">
                                            @if($service->ikon)
                                                <div style="width: 40px; height: 40px; min-width: 40px;" class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded">
                                                    <i class="{{ $service->ikon }} text-warning"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <input type="checkbox" name="ek_hizmetler[]" value="{{ $service->id }}" 
                                                    class="form-check-input bg-dark border-secondary service-checkbox float-end" 
                                                    id="service_{{ $service->id }}"
                                                    data-price="{{ $service->fiyat ?? 0 }}">
                                                <label class="form-check-label text-white d-block" for="service_{{ $service->id }}">
                                                    <strong>{{ $service->baslik }}</strong>
                                                    @if($service->fiyat > 0)
                                                        <span class="text-warning d-block small">{{ number_format($service->fiyat, 0, ',', '.') }} ₺</span>
                                                    @endif
                                                    @if($service->aciklama)
                                                        <small class="text-white-50 d-block mt-1">{{ Str::limit($service->aciklama, 60) }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Ödeme & Notlar -->
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-credit-card text-warning me-2"></i>Ödeme</h5>
                            <div class="mb-3">
                                <label class="form-label text-white-50">Ödeme Yöntemi</label>
                                <select name="odeme_tipi" class="form-select bg-dark border-secondary text-white">
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->baslik }}">{{ $method->baslik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">Notunuz</label>
                                <textarea name="notu" class="form-control bg-dark border-secondary text-white" rows="3" placeholder="Varsa özel isteklerinizi yazabilirsiniz...">{{ old('notu') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky-wrapper">
                        <div class="reservation-summary" id="sticky-sidebar">
                        <div class="villa-mini-card">
                            @if($villa->gorsel)
                                <img src="{{ asset('storage/villas/' . $villa->gorsel) }}" alt="">
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1 text-white">{{ $villa->baslik }}</h6>
                                <small class="text-white-50">
                                    <i class="fas fa-map-marker-alt"></i> {{ $villa->region->baslik ?? '' }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-white-50">Gecelik fiyat</small>
                            @if($villa->fiyat > 0)
                                <h4 class="fw-bold text-warning mb-0" id="base-price-display">{{ number_format($villa->fiyat, 0, ',', '.') }} ₺</h4>
                            @else
                                <div id="base-price-display">
                                    <h5 class="fw-bold text-warning mb-0">Tarih Seçiniz</h5>
                                    <small class="text-white-50" style="font-size: 0.8rem;">Fiyat tarih aralığına göre hesaplanacaktır.</small>
                                </div>
                            @endif
                        </div>
                        
                        <div id="summary-details" class="d-none">
                            <hr class="border-secondary">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Gece Sayısı:</span>
                                <strong id="summary-nights" class="text-white">-</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Toplam:</span>
                                <strong class="text-warning" id="summary-total">-</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50">Ön Ödeme ({{ $settings->on_kiralama_bedeli ?? 20 }}%):</span>
                                <strong id="summary-prepay" class="text-white">-</strong>
                            </div>
                        </div>
                        
                        <hr class="border-secondary">
                        
                        <button type="submit" class="btn btn-gold btn-lg w-100">
                            <i class="fas fa-check me-2"></i>Rezervasyon Talebi Gönder
                        </button>
                        
                        <p class="text-white-50 small mt-3 mb-0">
                            <i class="fas fa-shield-alt me-1"></i>
                            Rezervasyonunuz onaylandıktan sonra sizinle iletişime geçilecektir.
                        </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize Flatpickr (Datepicker) - Linked for reservation form (copied from villa detail page)
    document.addEventListener('DOMContentLoaded', function() {
        var villaId = {{ $villa->id }};
        var occupiedDates = [];
        
        // Get initial values from URL params
        var urlParams = new URLSearchParams(window.location.search);
        var initialCheckin = urlParams.get('gelis');
        var initialCheckout = urlParams.get('cikis');
        
        const checkinReservation = flatpickr("#gelis_tarihi", {
            locale: "tr",
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: initialCheckin || null,
            altInput: true,
            altFormat: "d F Y",
            theme: "dark",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutReservation.set('minDate', nextDay);
                    
                    // Auto open checkout if empty
                    if (!document.getElementById('cikis_tarihi').value) {
                        setTimeout(() => checkoutReservation.open(), 100);
                    }
                }
                calculatePrice();
                checkAvailability();
            }
        });
        
        const checkoutReservation = flatpickr("#cikis_tarihi", {
            locale: "tr",
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: initialCheckout || null,
            altInput: true,
            altFormat: "d F Y",
            theme: "dark",
            onChange: function(selectedDates, dateStr, instance) {
                calculatePrice();
                checkAvailability();
            }
        });
        
        // Load occupied dates and update pickers
        $.post('{{ route("api.occupied-dates") }}', {
            villa_id: villaId
        }, function(data) {
            occupiedDates = data.occupied || [];
            checkinReservation.set('disable', occupiedDates);
            checkoutReservation.set('disable', occupiedDates);
        });
        
        // Müsaitlik Kontrol Et
        function checkAvailability() {
            var gelis = document.getElementById('gelis_tarihi').value;
            var cikis = document.getElementById('cikis_tarihi').value;
            var message = document.getElementById('availability-message');
            
            if (!gelis || !cikis) {
                message.innerHTML = '<span class="text-white-50">Tarih seçim yapınız</span>';
                return;
            }
            
            try {
                var gelisDate = new Date(gelis + 'T00:00:00');
                var cikisDate = new Date(cikis + 'T00:00:00');
                
                var isOccupied = false;
                var current = new Date(gelisDate);
                
                while (current < cikisDate) {
                    var dateStr = current.toISOString().split('T')[0];
                    if (occupiedDates.includes(dateStr)) {
                        isOccupied = true;
                        break;
                    }
                    current.setDate(current.getDate() + 1);
                }
                
                if (isOccupied) {
                    message.innerHTML = '<div class="d-flex align-items-center"><i class="fas fa-times-circle text-danger me-2"></i><span class="text-danger fw-bold">Seçilen tarihler için villa müsait değil</span></div>';
                } else {
                    message.innerHTML = '<div class="d-flex align-items-center"><i class="fas fa-check-circle text-success me-2"></i><span class="text-success fw-bold">Villa müsait - Rezervasyon yapabilirsiniz</span></div>';
                }
            } catch(e) {
                console.error('Doluluk kontrolü hatası:', e);
            }
        }
        
        // Price Calculation Function
        function calculatePrice() {
            var gelis = document.getElementById('gelis_tarihi').value;
            var cikis = document.getElementById('cikis_tarihi').value;
            
            var summaryDetails = document.getElementById('summary-details');
            if (summaryDetails) summaryDetails.classList.remove('d-none');
            
            if (!gelis || !cikis) {
                document.getElementById('summary-nights').textContent = '-';
                document.getElementById('summary-total').textContent = '-';
                document.getElementById('summary-prepay').textContent = '-';
                return;
            }
            
            var servicesTotal = 0;
            document.querySelectorAll('.service-checkbox:checked').forEach(function(checkbox) {
                servicesTotal += parseFloat(checkbox.dataset.price);
            });
            
            $.post('{{ route("api.calculate-price") }}', {
                villa_id: villaId,
                gelis: gelis,
                cikis: cikis
            }, function(data) {
                var grandTotal = data.total + servicesTotal;
                var prepayment = (grandTotal * {{ $settings->on_kiralama_bedeli ?? 20 }}) / 100;
                var formatter = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', minimumFractionDigits: 0 });
                
                document.getElementById('night-count').textContent = data.nights;
                document.getElementById('total-price').textContent = formatter.format(grandTotal);
                document.getElementById('prepayment').textContent = formatter.format(prepayment);
                document.getElementById('price-info').style.display = 'block';
                
                document.getElementById('summary-nights').textContent = data.nights + ' gece';
                document.getElementById('summary-total').textContent = formatter.format(grandTotal);
                document.getElementById('summary-prepay').textContent = formatter.format(prepayment);
            });
        }
        
        // Listen for service changes
        document.querySelectorAll('.service-checkbox, select[name="yetiskin"], select[name="cocuk"]').forEach(function(el) {
            el.addEventListener('change', function() {
                calculatePrice();
            });
        });
        
        // Initial calculation if dates are pre-filled
        if (initialCheckin && initialCheckout) {
            setTimeout(function() {
                calculatePrice();
                checkAvailability();
            }, 300);
        }
    });
</script>
@endpush
