<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'siteadi', 'anahtar_kelime', 'aciklama', 'site_dogrulama', 'sayac_kodu',
        'mailsunucu', 'port', 'email', 'sifre', 'map', 'sitemap', 'baslik', 'kisa',
        'facebook', 'twitter', 'instagram', 'facebook_sayfa_adi', 'facebook_app_id',
        'twitter_sayfa_adi', 'youtube', 'menu_ustu_adres', 'telefon1', 'iletisim_adres',
        'telefon2', 'iletisim_email', 'popup', 'popup_icerik', 'whatsapp',
        'sosyal_aciklama', 'footer_aciklama', 'menu', 'blog', 'ekip', 'hakkimizda',
        'kariyer', 'uzmanlik', 'kariyer_yazi', 'ekip_yazi', 'branslar',
        'site_anahtari', 'gizli_anahtar', 'watermark', 'dil_durum',
        'ingilzce', 'fransa', 'almanca', 'italyanca', 'rusca', 'ispanyolca', 'arapca',
        'sozlesme_metni_1', 'sozlesme_metni_2', 'on_kiralama_bedeli',
        'hesap_no', 'yetkili_kisi',
        'ileti_merkezi_username', 'ileti_merkezi_password', 'ileti_merkezi_sender',
        'anasayfa_video', // Added video field
    ];

    // Singleton pattern for settings
    public static function instance()
    {
        return static::first() ?? new static();
    }
}
