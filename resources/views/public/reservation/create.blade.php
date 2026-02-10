@extends('public.layouts.master')

@section('title', 'Rezervasyon - ' . $villa->baslik)

@push('styles')
<style>
.reservation-summary, .sticky-top-custom {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    padding: 2rem;
    position: -webkit-sticky; /* Safari */
    position: sticky;
    top: 140px; /* Increased to avoid header overlap */
    color: #fff;
    border-radius: 20px;
    z-index: 90;
    width: 100%; /* Ensure full width */
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
.flatpickr-calendar { background: #1a1a1a !important; border: 1px solid #333 !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; z-index: 9999 !important; }
.flatpickr-day { color: #fff !important; }
.flatpickr-day:hover { background: #333 !important; }
.flatpickr-day.selected { background: var(--gold-accent) !important; border-color: var(--gold-accent) !important; color: #000 !important; }

/* Header & Text Colors */
.flatpickr-months .flatpickr-month { background: #1a1a1a !important; color: #fff !important; fill: #fff !important; }
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year { color: #fff !important; font-weight: 600; }
.flatpickr-current-month .flatpickr-monthDropdown-months:hover,
.flatpickr-current-month input.cur-year:hover { background: rgba(255,255,255,0.1); }
.flatpickr-weekdays { background: #1a1a1a !important; }
span.flatpickr-weekday { color: #888 !important; }

/* Arrows */
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month { color: #fff !important; fill: #fff !important; }
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
            <div class="row">
                <div class="col-lg-8">
                    <!-- Tarih Seçimi -->
                    <div class="card border-secondary border-opacity-25 shadow-sm mb-4" style="background-color: #1a1a1a; border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-white"><i class="fas fa-calendar-alt text-warning me-2"></i>Tarih Seçimi</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Giriş Tarihi *</label>
                                    <input type="text" name="gelis_tarihi" id="gelis_tarihi" class="form-control bg-dark border-secondary text-white datepicker" 
                                        value="{{ old('gelis_tarihi', $gelis) }}" required placeholder="Tarih seçin">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-white-50">Çıkış Tarihi *</label>
                                    <input type="text" name="cikis_tarihi" id="cikis_tarihi" class="form-control bg-dark border-secondary text-white datepicker" 
                                        value="{{ old('cikis_tarihi', $cikis) }}" required placeholder="Tarih seçin">
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
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="ek_hizmetler[]" value="{{ $service->id }}" 
                                            class="form-check-input bg-dark border-secondary service-checkbox" 
                                            id="service_{{ $service->id }}"
                                            data-price="{{ $service->fiyat ?? 0 }}">
                                        <label class="form-check-label text-white-50" for="service_{{ $service->id }}">
                                            {{ $service->baslik }} 
                                            @if($service->fiyat > 0)
                                                <small class="text-warning">({{ number_format($service->fiyat, 0, ',', '.') }} ₺)</small>
                                            @endif
                                        </label>
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
                <div class="col-lg-4 h-100" id="sidebar-container">
                    <div class="reservation-summary sticky-top-custom" id="sticky-sidebar">
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
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
.reservation-summary, .sticky-top-custom {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    padding: 2rem;
    position: relative; /* Default */
    color: #fff;
    border-radius: 20px;
    z-index: 90;
    width: 100%;
}

.is-sticky {
    position: fixed !important;
    top: 140px;
    z-index: 99;
}
</style>
@endpush

@push('scripts')
<script>
    // JS Fallback for Sticky Sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sticky-sidebar');
        const container = document.getElementById('sidebar-container');
        
        if (sidebar && container) {
            const offsetTop = 140; // Desired top margin
            
            window.addEventListener('scroll', function() {
                const containerRect = container.getBoundingClientRect();
                const sidebarRect = sidebar.getBoundingClientRect();
                
                // If container top is above the offset (scrolled past), start sticking
                if (containerRect.top < offsetTop) {
                    sidebar.classList.add('is-sticky');
                    // Match width of container
                    sidebar.style.width = container.offsetWidth + 'px';
                } else {
                    sidebar.classList.remove('is-sticky');
                    sidebar.style.width = '100%';
                }
            });
            
            // Handle resize
            window.addEventListener('resize', function() {
                if (sidebar.classList.contains('is-sticky')) {
                    sidebar.style.width = container.offsetWidth + 'px';
                }
            });
        }
    });

    $(document).ready(function() {
    var villaId = {{ $villa->id }};
    var occupiedDates = [];
    
    // Get occupied dates
    $.post('{{ route("api.occupied-dates") }}', { villa_id: villaId }, function(data) {
        occupiedDates = data.occupied;
        initDatepickers();
    }).fail(function() {
        console.error('Failed to fetch occupied dates');
        initDatepickers();
    });
    
    function initDatepickers() {
        const checkinPicker = flatpickr('#gelis_tarihi', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            defaultDate: 'today',
            disable: occupiedDates,
            locale: 'tr',
            onChange: function(dates, dateStr) {
                if (dates.length > 0) {
                    const nextDay = new Date(dates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutPicker.set('minDate', nextDay);
                    
                    // Auto open checkout if empty
                    if ($('#cikis_tarihi').val() === '') {
                        setTimeout(() => checkoutPicker.open(), 100);
                    }
                }
                calculatePrice();
            }
        });
        
        const checkoutPicker = flatpickr('#cikis_tarihi', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            defaultDate: 'today',
            disable: occupiedDates,
            locale: 'tr',
            onChange: function(dates, dateStr) {
                calculatePrice();
            }
        });
    }
    
    // Listen for changes in services and guests
    $('.service-checkbox, select[name="yetiskin"], select[name="cocuk"]').on('change', function() {
        calculatePrice();
    });
    
    function calculatePrice() {
        var gelis = $('#gelis_tarihi').val();
        var cikis = $('#cikis_tarihi').val();
        
        // Show summary even if dates are not selected (with 0 values)
        $('#summary-details').removeClass('d-none'); // Always show
        
        if (!gelis || !cikis) {
            $('#summary-nights').text('-');
            $('#summary-total').text('-');
            $('#summary-prepay').text('-');
            return;
        }
        
        // Calculate services total
        var servicesTotal = 0;
        $('.service-checkbox:checked').each(function() {
            servicesTotal += parseFloat($(this).data('price'));
        });
        
        $.post('{{ route("api.calculate-price") }}', {
            villa_id: villaId,
            gelis: gelis,
            cikis: cikis
        }, function(data) {
            // Add services cost to total
            var grandTotal = data.total + servicesTotal;
            var prepayment = (grandTotal * {{ $settings->on_kiralama_bedeli ?? 20 }}) / 100;
            
            // Format currency
            var formatter = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', minimumFractionDigits: 0 });
            
            $('#night-count').text(data.nights);
            $('#total-price').text(formatter.format(grandTotal));
            $('#prepayment').text(formatter.format(prepayment));
            $('#price-info').show();
            
            $('#summary-nights').text(data.nights + ' gece');
            $('#summary-total').text(formatter.format(grandTotal));
            $('#summary-prepay').text(formatter.format(prepayment));
        });
    }
    
    // Initial Calc
    if ($('#gelis_tarihi').val() && $('#cikis_tarihi').val()) {
        calculatePrice();
    } else {
        // Ensure summary is visible but empty
        $('#summary-details').removeClass('d-none');
    }
});
</script>
@endpush
