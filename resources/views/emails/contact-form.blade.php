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
        .message-box { background: #fff; border-left: 4px solid #2c5f2d; padding: 15px; margin: 15px 0; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .label { color: #666; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $settings->siteadi ?? 'Villa Kiralama' }}</h1>
            <p>Yeni İletişim Formu Mesajı</p>
        </div>
        
        <div class="content">
            <div class="info-box">
                <p><span class="label">Ad Soyad:</span> {{ $contact->adsoyad }}</p>
                <p><span class="label">E-posta:</span> {{ $contact->email }}</p>
                <p><span class="label">Telefon:</span> {{ $contact->telefon }}</p>
                <p><span class="label">Konu:</span> {{ $contact->konu }}</p>
                <p><span class="label">Tarih:</span> {{ $contact->created_at->format('d.m.Y H:i') }}</p>
            </div>
            
            <h3>Mesaj:</h3>
            <div class="message-box">
                {!! nl2br(e($contact->mesaj)) !!}
            </div>
            
            <p style="margin-top: 20px;">
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->konu }}" 
                   style="background: #2c5f2d; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    Yanıtla
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>Bu e-posta {{ $settings->siteadi ?? 'Villa Kiralama' }} iletişim formundan gönderilmiştir.</p>
        </div>
    </div>
</body>
</html>
