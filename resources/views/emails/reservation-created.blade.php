<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c5f2d; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .info-box { background: #fff; border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #666; }
        .value { font-weight: bold; }
        .total { font-size: 1.2em; color: #2c5f2d; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .btn { display: inline-block; background: #2c5f2d; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $settings->siteadi ?? 'Villa Kiralama' }}</h1>
            <p>Yeni Rezervasyon Talebi</p>
        </div>
        
        <div class="content">
            <h2>Rezervasyon #{{ $reservation->id }}</h2>
            
            <div class="info-box">
                <h3>Villa Bilgileri</h3>
                <div class="info-row">
                    <span class="label">Villa:</span>
                    <span class="value">{{ $reservation->villa->baslik }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Giriş Tarihi:</span>
                    <span class="value">{{ $reservation->gelis_tarihi->format('d.m.Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Çıkış Tarihi:</span>
                    <span class="value">{{ $reservation->cikis_tarihi->format('d.m.Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Gece Sayısı:</span>
                    <span class="value">{{ $reservation->night_count }} gece</span>
                </div>
                <div class="info-row">
                    <span class="label">Toplam Tutar:</span>
                    <span class="value total">{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</span>
                </div>
            </div>
            
            <div class="info-box">
                <h3>Müşteri Bilgileri</h3>
                <div class="info-row">
                    <span class="label">Ad Soyad:</span>
                    <span class="value">{{ $reservation->adsoyad }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Telefon:</span>
                    <span class="value">{{ $reservation->telefon }}</span>
                </div>
                <div class="info-row">
                    <span class="label">E-posta:</span>
                    <span class="value">{{ $reservation->email }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Yetişkin:</span>
                    <span class="value">{{ $reservation->yetiskin }} kişi</span>
                </div>
                <div class="info-row">
                    <span class="label">Çocuk:</span>
                    <span class="value">{{ $reservation->cocuk ?? 0 }} kişi</span>
                </div>
                @if($reservation->notu)
                <div class="info-row">
                    <span class="label">Not:</span>
                    <span class="value">{{ $reservation->notu }}</span>
                </div>
                @endif
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/admin/reservations/' . $reservation->id . '/edit') }}" class="btn">
                    Rezervasyonu Görüntüle
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>Bu e-posta {{ $settings->siteadi ?? 'Villa Kiralama' }} tarafından otomatik olarak gönderilmiştir.</p>
            <p>{{ $settings->iletisim_email ?? '' }} | {{ $settings->telefon1 ?? '' }}</p>
        </div>
    </div>
</body>
</html>
