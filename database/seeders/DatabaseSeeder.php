<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdditionalService;
use App\Models\PaymentMethod;
use App\Models\Region;
use App\Models\Setting;
use App\Models\TechnicalFeature;
use App\Models\VillaType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin (username: admin, password: admin)
        Admin::create([
            'kullanici_adi' => 'admin',
            'sifre' => md5('admin'), // Legacy MD5 format
            'email' => 'admin@example.com',
            'tel' => '(999) 999-99-99',
            'adsoyad' => 'Yönetici',
            'songiris' => now()->format('d.m.Y H:i'),
            'songirisip' => '127.0.0.1',
        ]);

        // Create default settings
        Setting::create([
            'siteadi' => 'Villa Kiralama',
            'anahtar_kelime' => 'kiralık villa, kiralık villar',
            'aciklama' => 'Villa kiralama sitesi',
            'telefon1' => '0537 000 00 00',
            'iletisim_adres' => 'Merkez/Sinop',
            'iletisim_email' => 'info@example.com',
            'facebook' => 'https://facebook.com',
            'twitter' => 'https://twitter.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'whatsapp' => '905370000000',
            'on_kiralama_bedeli' => 20,
        ]);

        // Sample regions
        $regions = [
            ['baslik' => 'Sinop / Merkez', 'sef' => 'sinop-merkez'],
            ['baslik' => 'Gerze / Yalıkavak', 'sef' => 'gerze-yalikavak'],
            ['baslik' => 'Merkez / Bektaşağa', 'sef' => 'merkez-bektasaga'],
            ['baslik' => 'Ayancık / Nisi', 'sef' => 'ayancik-nisi'],
            ['baslik' => 'Boyabat / Ardıç', 'sef' => 'boyabat-ardic'],
        ];
        
        foreach ($regions as $region) {
            Region::create($region);
        }

        // Sample villa types
        $villaTypes = [
            ['baslik' => 'Geniş Aile Villa', 'sef' => 'genis-aile-villa'],
            ['baslik' => 'Çocuk Havuzlu Villa', 'sef' => 'cocuk-havuzlu-villa'],
            ['baslik' => 'Balayı Villaları', 'sef' => 'balayi-villalari'],
            ['baslik' => 'Deniz Manzaralı Villa', 'sef' => 'deniz-manzarali-villa'],
        ];
        
        foreach ($villaTypes as $type) {
            VillaType::create($type);
        }

        // Sample technical features
        $features = [
            ['baslik' => 'Isıtmalı Havuz', 'gosterim' => 1, 'ikon' => 'fa-circle-o'],
            ['baslik' => 'Müstakil Havuz', 'gosterim' => 1, 'ikon' => 'fa-bandcamp'],
            ['baslik' => 'Çocuk Havuzu', 'gosterim' => 1, 'ikon' => 'fa-sun-o'],
            ['baslik' => 'Jakuzi', 'gosterim' => 1, 'ikon' => 'fa-certificate'],
            ['baslik' => 'Özel Bahçe', 'gosterim' => 0],
            ['baslik' => 'Veranda / Teras', 'gosterim' => 0],
            ['baslik' => 'Amerikan Mutfak', 'gosterim' => 0],
            ['baslik' => 'Beyaz Eşya', 'gosterim' => 0],
            ['baslik' => 'Televizyon', 'gosterim' => 0],
            ['baslik' => 'Klima', 'gosterim' => 0],
        ];
        
        foreach ($features as $feature) {
            TechnicalFeature::create($feature);
        }

        // Sample payment methods
        $paymentMethods = [
            ['baslik' => 'Villada ödeme'],
            ['baslik' => 'Post İle'],
            ['baslik' => 'Havale/Eft İle'],
        ];
        
        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        // Sample additional services
        $services = [
            ['baslik' => 'Temizlik'],
            ['baslik' => 'Servis'],
            ['baslik' => 'Sıcak Su'],
            ['baslik' => 'Isıtmalı Jakuzi'],
        ];
        
        foreach ($services as $service) {
            AdditionalService::create($service);
        }
    }
}
