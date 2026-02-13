@extends('admin.layouts.master')

@section('title', 'Genel Ayarlar - Yönetim Paneli')
@section('page-title', 'Genel Ayarlar')

@section('breadcrumb')
<li class="breadcrumb-item active">Genel Ayarlar</li>
@endsection

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="settings-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#site-bilgileri">
                        <i class="fas fa-globe me-1"></i>Site Bilgileri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#logolar">
                        <i class="fas fa-image me-1"></i>Logolar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#home-video">
                        <i class="fas fa-video me-1"></i>Anasayfa Video
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#email-sms">
                        <i class="fas fa-envelope me-1"></i>Email & SMS
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sosyal-medya">
                        <i class="fab fa-facebook me-1"></i>Sosyal Medya
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#iletisim">
                        <i class="fas fa-phone me-1"></i>İletişim
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#recaptcha">
                        <i class="fas fa-shield-alt me-1"></i>Recaptcha
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#watermark">
                        <i class="fas fa-copyright me-1"></i>Watermark
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#dil-ayarlari">
                        <i class="fas fa-language me-1"></i>Dil Ayarları
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sozlesme">
                        <i class="fas fa-file-contract me-1"></i>Sözleşme
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                <!-- Site Bilgileri -->
                <div class="tab-pane fade show active" id="site-bilgileri">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Adı</label>
                                <input type="text" name="siteadi" class="form-control" value="{{ $settings->siteadi }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Site Başlığı (Title)</label>
                                <input type="text" name="title" class="form-control" value="{{ $settings->title }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Site Açıklaması (Description)</label>
                                <textarea name="description" class="form-control" rows="3">{{ $settings->description }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Anahtar Kelimeler (Keywords)</label>
                                <textarea name="keywords" class="form-control" rows="2">{{ $settings->keywords }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Google Analytics Kodu</label>
                                <textarea name="google_analytics" class="form-control" rows="4" placeholder="UA-XXXXX-X veya G-XXXXXXXXXX">{{ $settings->google_analytics }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Bakım Modu</label>
                                <select name="maintenance_mode" class="form-select">
                                    <option value="0" {{ $settings->maintenance_mode == 0 ? 'selected' : '' }}>Kapalı</option>
                                    <option value="1" {{ $settings->maintenance_mode == 1 ? 'selected' : '' }}>Açık</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Logolar -->
                <div class="tab-pane fade" id="logolar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Logosu</label>
                                @if($settings->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" style="max-height: 80px;">
                                </div>
                                @endif
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <small class="text-muted">Önerilen boyut: 200x60 px</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Favicon</label>
                                @if($settings->favicon)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Favicon" style="max-height: 32px;">
                                </div>
                                @endif
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                                <small class="text-muted">Önerilen boyut: 32x32 px (ICO veya PNG)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Footer Logosu</label>
                                @if($settings->footer_logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings->footer_logo) }}" alt="Footer Logo" style="max-height: 80px;">
                                </div>
                                @endif
                                <input type="file" name="footer_logo" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Anasayfa Video -->
                <div class="tab-pane fade" id="home-video">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Bu video anasayfada otomatik olarak oynatılacaktır.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Promosyon Videosu (.mp4)</label>
                                @if($settings->anasayfa_video)
                                <div class="mb-3">
                                    <video width="320" height="240" controls>
                                        <source src="{{ asset('storage/' . $settings->anasayfa_video) }}" type="video/mp4">
                                        Tarayıcınız video etiketini desteklemiyor.
                                    </video>
                                    <div class="mt-2 text-muted small">
                                        Mevcut Video: {{ $settings->anasayfa_video }}
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="anasayfa_video" class="form-control" accept="video/mp4,video/webm">
                                <small class="text-muted">Önerilen Çözünürlük: 1920x1080 (HD). Dosya boyutu sunucu limitlerine tabidir.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email & SMS -->
                <div class="tab-pane fade" id="email-sms">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-envelope me-2"></i>SMTP Ayarları</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">SMTP Sunucu</label>
                                <input type="text" name="mailsunucu" class="form-control" value="{{ $settings->mailsunucu }}" placeholder="smtp.example.com">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Port</label>
                                <input type="text" name="port" class="form-control" value="{{ $settings->port }}" placeholder="587">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">E-posta Adresi</label>
                                <input type="email" name="email" class="form-control" value="{{ $settings->email }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Şifre</label>
                                <input type="password" name="sifre" class="form-control" value="{{ $settings->sifre }}">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-sms me-2"></i>İleti Merkezi SMS Ayarları</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Kullanıcı Adı</label>
                                <input type="text" name="ileti_merkezi_username" class="form-control" value="{{ $settings->ileti_merkezi_username }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Şifre</label>
                                <input type="password" name="ileti_merkezi_password" class="form-control" value="{{ $settings->ileti_merkezi_password }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Gönderici Adı (Sender)</label>
                                <input type="text" name="ileti_merkezi_sender" class="form-control" value="{{ $settings->ileti_merkezi_sender }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sosyal Medya -->
                <div class="tab-pane fade" id="sosyal-medya">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-facebook text-primary me-2"></i>Facebook</label>
                                <input type="url" name="facebook" class="form-control" value="{{ $settings->facebook }}" placeholder="https://facebook.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-twitter text-info me-2"></i>Twitter</label>
                                <input type="url" name="twitter" class="form-control" value="{{ $settings->twitter }}" placeholder="https://twitter.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger me-2"></i>Instagram</label>
                                <input type="url" name="instagram" class="form-control" value="{{ $settings->instagram }}" placeholder="https://instagram.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-pinterest text-danger me-2"></i>Pinterest</label>
                                <input type="url" name="pinterest" class="form-control" value="{{ $settings->pinterest }}" placeholder="https://pinterest.com/...">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-youtube text-danger me-2"></i>YouTube</label>
                                <input type="url" name="youtube" class="form-control" value="{{ $settings->youtube }}" placeholder="https://youtube.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-linkedin text-primary me-2"></i>LinkedIn</label>
                                <input type="url" name="linkedin" class="form-control" value="{{ $settings->linkedin }}" placeholder="https://linkedin.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" value="{{ $settings->whatsapp }}" placeholder="905xxxxxxxxx">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-google text-warning me-2"></i>Google</label>
                                <input type="url" name="google" class="form-control" value="{{ $settings->google }}" placeholder="https://google.com/...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- İletişim Bilgileri -->
                <div class="tab-pane fade" id="iletisim">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Telefon 1</label>
                                <input type="text" name="telefon1" class="form-control" value="{{ $settings->telefon1 }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fab fa-whatsapp text-success me-2"></i>Telefon 2 (WhatsApp)</label>
                                <input type="text" name="telefon2" class="form-control" value="{{ $settings->telefon2 }}" placeholder="905xxxxxxxxx">
                                <small class="text-muted">Bu numara WhatsApp butonunda kullanılacaktır.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">E-posta (İletişim)</label>
                                <input type="email" name="iletisim_email" class="form-control" value="{{ $settings->iletisim_email }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adres</label>
                                <textarea name="iletisim_adres" class="form-control" rows="3">{{ $settings->iletisim_adres }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Google Maps Embed Kodu</label>
                                <textarea name="harita" class="form-control" rows="6" placeholder="<iframe src='...'></iframe>">{{ $settings->harita }}</textarea>
                                <small class="text-muted">Google Maps'ten "Paylaş > Haritayı Yerleştir" kodunu buraya yapıştırın</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recaptcha -->
                <div class="tab-pane fade" id="recaptcha">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                reCAPTCHA v2 anahtarlarını <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a> sayfasından alabilirsiniz.
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Site Key</label>
                                <input type="text" name="recaptcha_site_key" class="form-control" value="{{ $settings->recaptcha_site_key }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Secret Key</label>
                                <input type="text" name="recaptcha_secret_key" class="form-control" value="{{ $settings->recaptcha_secret_key }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Watermark -->
                <div class="tab-pane fade" id="watermark">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Watermark Durumu</label>
                                <select name="watermark_durum" class="form-select">
                                    <option value="0" {{ ($settings->watermark_durum ?? 0) == 0 ? 'selected' : '' }}>Kapalı</option>
                                    <option value="1" {{ ($settings->watermark_durum ?? 0) == 1 ? 'selected' : '' }}>Açık</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Watermark Metni</label>
                                <input type="text" name="watermark_text" class="form-control" value="{{ $settings->watermark_text }}" placeholder="© Site Adı">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dil Ayarları -->
                <div class="tab-pane fade" id="dil-ayarlari">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Varsayılan Dil</label>
                                <select name="varsayilan_dil" class="form-select">
                                    <option value="tr" {{ ($settings->varsayilan_dil ?? 'tr') == 'tr' ? 'selected' : '' }}>Türkçe</option>
                                    <option value="en" {{ ($settings->varsayilan_dil ?? 'tr') == 'en' ? 'selected' : '' }}>İngilizce</option>
                                    <option value="de" {{ ($settings->varsayilan_dil ?? 'tr') == 'de' ? 'selected' : '' }}>Almanca</option>
                                    <option value="ru" {{ ($settings->varsayilan_dil ?? 'tr') == 'ru' ? 'selected' : '' }}>Rusça</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dil Seçeneği Göster</label>
                                <select name="dil_durum" class="form-select">
                                    <option value="0" {{ ($settings->dil_durum ?? 0) == 0 ? 'selected' : '' }}>Hayır</option>
                                    <option value="1" {{ ($settings->dil_durum ?? 0) == 1 ? 'selected' : '' }}>Evet</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Aktif Diller</h6>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="ingilizce" value="1" id="lang-en" {{ ($settings->ingilzce ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-en">İngilizce</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="almanca" value="1" id="lang-de" {{ ($settings->almanca ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-de">Almanca</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="rusca" value="1" id="lang-ru" {{ ($settings->rusca ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-ru">Rusça</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="fransa" value="1" id="lang-fr" {{ ($settings->fransa ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-fr">Fransızca</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="ispanyolca" value="1" id="lang-es" {{ ($settings->ispanyolca ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-es">İspanyolca</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="italyanca" value="1" id="lang-it" {{ ($settings->italyanca ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-it">İtalyanca</label>
                            </div>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="arapca" value="1" id="lang-ar" {{ ($settings->arapca ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="lang-ar">Arapça</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sözleşme -->
                <div class="tab-pane fade" id="sozlesme">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Hesap Numarası</label>
                                <input type="text" name="hesap_no" class="form-control" value="{{ $settings->hesap_no }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Yetkili Kişi</label>
                                <input type="text" name="yetkili_kisi" class="form-control" value="{{ $settings->yetkili_kisi }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ön Kiralama Bedeli (%)</label>
                                <input type="number" name="on_kiralama_bedeli" class="form-control" value="{{ $settings->on_kiralama_bedeli }}" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sözleşme Metni</label>
                        <textarea name="sozlesme" id="sozlesme-editor" class="form-control" rows="15">{{ $settings->sozlesme }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Ayarları Kaydet
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // Initialize CKEditor for sözleşme
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#sozlesme-editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
            })
            .catch(error => console.error(error));
    }
</script>
@endsection
