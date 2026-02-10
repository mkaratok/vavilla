<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c5f2d; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .success-icon { font-size: 48px; color: #28a745; text-align: center; margin: 20px 0; }
        .info-box { background: #fff; border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #666; }
        .value { font-weight: bold; }
        .total { font-size: 1.2em; color: #2c5f2d; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .alert { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $settings->siteadi ?? 'Villa Kiralama' }}</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">✓</div>
            <h2 style="text-align: center; color: #28a745;">Rezervasyonunuz Onaylandı!</h2>
            
            <p>Sayın {{ $reservation->adsoyad }},</p>
            <p>Rezervasyonunuz başarıyla onaylanmıştır. Tatil detaylarınız aşağıda yer almaktadır.</p>
            
            <div class="info-box">
                <h3>Rezervasyon Detayları</h3>
                <div class="info-row">
                    <span class="label">Rezervasyon No:</span>
                    <span class="value">#{{ $reservation->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Villa:</span>
                    <span class="value">{{ $reservation->villa->baslik }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Konum:</span>
                    <span class="value">{{ $reservation->villa->region->baslik ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Giriş Tarihi:</span>
                    <span class="value">{{ $reservation->gelis_tarihi->format('d.m.Y') }} (14:00'dan itibaren)</span>
                </div>
                <div class="info-row">
                    <span class="label">Çıkış Tarihi:</span>
                    <span class="value">{{ $reservation->cikis_tarihi->format('d.m.Y') }} (10:00'a kadar)</span>
                </div>
                <div class="info-row">
                    <span class="label">Konaklama:</span>
                    <span class="value">{{ $reservation->night_count }} gece</span>
                </div>
                <div class="info-row">
                    <span class="label">Misafir:</span>
                    <span class="value">{{ $reservation->yetiskin }} yetişkin, {{ $reservation->cocuk ?? 0 }} çocuk</span>
                </div>
            </div>
            
            <div class="info-box">
                <h3>Ödeme Bilgileri</h3>
                <div class="info-row">
                    <span class="label">Toplam Tutar:</span>
                    <span class="value total">{{ number_format($reservation->toplam_tutar, 0, ',', '.') }} ₺</span>
                </div>
                <div class="info-row">
                    <span class="label">Ödeme Yöntemi:</span>
                    <span class="value">{{ $reservation->odeme_tipi }}</span>
                </div>
            </div>
            
            <div class="alert">
                <strong>Önemli Bilgiler:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Giriş saati: 14:00</li>
                    <li>Çıkış saati: 10:00</li>
                    <li>Yanınızda kimlik belgesi bulundurunuz</li>
                </ul>
            </div>
            
            <p>Sorularınız için bize ulaşabilirsiniz:</p>
            <p>
                <strong>Telefon:</strong> {{ $settings->telefon1 ?? '' }}<br>
                <strong>E-posta:</strong> {{ $settings->iletisim_email ?? '' }}
            </p>
            
            <p>Keyifli bir tatil dileriz!</p>
        </div>
        
        <div class="footer">
            <p>{{ $settings->siteadi ?? 'Villa Kiralama' }}</p>
            <p>{{ $settings->iletisim_adres ?? '' }}</p>
        </div>
    </div>
</body>
</html>
