<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\PaymentMethod;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\TechnicalFeature;
use App\Models\AdditionalService;
use App\Models\Villa;
use App\Models\VillaImage;
use App\Models\VillaSeasonalPrice;
use App\Models\VillaType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateLegacyData extends Command
{
    protected $signature = 'migrate:legacy 
                            {--connection=legacy : Legacy database connection name}
                            {--images-path= : Path to legacy images folder}
                            {--dry-run : Run without actually inserting data}';

    protected $description = 'Migrate data from legacy villa admin database';

    protected $legacyConnection;
    protected $isDryRun = false;
    protected $imagesPath = null;
    protected $stats = [];

    public function handle()
    {
        $this->legacyConnection = $this->option('connection');
        $this->isDryRun = $this->option('dry-run');
        $this->imagesPath = $this->option('images-path');

        $this->info('Starting legacy data migration...');
        
        if ($this->isDryRun) {
            $this->warn('DRY RUN MODE - No data will be inserted');
        }

        try {
            // Test legacy connection
            $this->testConnection();

            // Migrate in order of dependencies
            $this->migrateSettings();
            $this->migrateAdmins();
            $this->migrateRegions();
            $this->migrateVillaTypes();
            $this->migrateTechnicalFeatures();
            $this->migrateAdditionalServices();
            $this->migratePaymentMethods();
            $this->migrateVillas();
            $this->migrateVillaImages();
            $this->migrateVillaSeasonalPrices();
            $this->migrateReservations();
            $this->migrateContacts();
            $this->migrateNewsletters();

            // Copy images if path provided
            if ($this->imagesPath) {
                $this->copyImages();
            }

            $this->displayStats();
            $this->info('Migration completed successfully!');

        } catch (\Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    protected function testConnection()
    {
        $this->info('Testing legacy database connection...');
        
        try {
            DB::connection($this->legacyConnection)->getPdo();
            $this->info('Connection successful!');
        } catch (\Exception $e) {
            throw new \Exception('Could not connect to legacy database: ' . $e->getMessage());
        }
    }

    protected function migrateSettings()
    {
        $this->info('Migrating settings...');
        
        $legacySettings = DB::connection($this->legacyConnection)
            ->table('blm_genel')
            ->first();

        if (!$legacySettings) {
            $this->warn('No settings found in legacy database');
            return;
        }

        if ($this->isDryRun) {
            $this->stats['settings'] = 1;
            return;
        }

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'siteadi' => $legacySettings->siteadi ?? '',
                'title' => $legacySettings->title ?? '',
                'description' => $legacySettings->description ?? '',
                'keywords' => $legacySettings->keywords ?? '',
                'telefon1' => $legacySettings->telefon1 ?? '',
                'telefon2' => $legacySettings->telefon2 ?? '',
                'email' => $legacySettings->email ?? '',
                'iletisim_email' => $legacySettings->iletisim_email ?? '',
                'iletisim_adres' => $legacySettings->iletisim_adres ?? '',
                'facebook' => $legacySettings->facebook ?? '',
                'twitter' => $legacySettings->twitter ?? '',
                'instagram' => $legacySettings->instagram ?? '',
                'youtube' => $legacySettings->youtube ?? '',
                'google_analytics' => $legacySettings->google_analytics ?? '',
                'harita' => $legacySettings->harita ?? '',
                'recaptcha_site_key' => $legacySettings->recaptcha_site_key ?? '',
                'recaptcha_secret_key' => $legacySettings->recaptcha_secret_key ?? '',
                'maintenance_mode' => $legacySettings->bakim ?? 0,
                'on_kiralama_bedeli' => $legacySettings->on_kiralama_bedeli ?? 30,
            ]
        );

        $this->stats['settings'] = 1;
    }

    protected function migrateAdmins()
    {
        $this->info('Migrating admins...');
        
        $legacyAdmins = DB::connection($this->legacyConnection)
            ->table('blm_adminler')
            ->get();

        $count = 0;
        foreach ($legacyAdmins as $admin) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            Admin::updateOrCreate(
                ['kullanici' => $admin->kullanici],
                [
                    'sifre' => $admin->sifre, // Keep MD5 hash as-is
                    'ad' => $admin->ad ?? '',
                    'email' => $admin->email ?? '',
                    'durum' => $admin->durum ?? 1,
                ]
            );
            $count++;
        }

        $this->stats['admins'] = $count;
    }

    protected function migrateRegions()
    {
        $this->info('Migrating regions...');
        
        $legacyRegions = DB::connection($this->legacyConnection)
            ->table('blm_bolgeler')
            ->get();

        $count = 0;
        foreach ($legacyRegions as $region) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            Region::updateOrCreate(
                ['id' => $region->id],
                [
                    'baslik' => $region->baslik ?? '',
                    'sef' => $region->sef ?? '',
                    'aciklama' => $region->aciklama ?? '',
                    'icon' => $region->icon ?? '',
                    'sira' => $region->sira ?? 0,
                    'title' => $region->title ?? '',
                    'description' => $region->description ?? '',
                    'keywords' => $region->keywords ?? '',
                ]
            );
            $count++;
        }

        $this->stats['regions'] = $count;
    }

    protected function migrateVillaTypes()
    {
        $this->info('Migrating villa types...');
        
        $legacyTypes = DB::connection($this->legacyConnection)
            ->table('blm_villa_turleri')
            ->get();

        $count = 0;
        foreach ($legacyTypes as $type) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            VillaType::updateOrCreate(
                ['id' => $type->id],
                [
                    'baslik' => $type->baslik ?? '',
                    'sef' => $type->sef ?? '',
                    'aciklama' => $type->aciklama ?? '',
                    'icon' => $type->icon ?? '',
                    'gorsel' => $type->gorsel ?? '',
                    'title' => $type->title ?? '',
                    'description' => $type->description ?? '',
                    'keywords' => $type->keywords ?? '',
                ]
            );
            $count++;
        }

        $this->stats['villa_types'] = $count;
    }

    protected function migrateTechnicalFeatures()
    {
        $this->info('Migrating technical features...');
        
        $legacyFeatures = DB::connection($this->legacyConnection)
            ->table('blm_teknik_ozellikler')
            ->get();

        $count = 0;
        foreach ($legacyFeatures as $feature) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            TechnicalFeature::updateOrCreate(
                ['id' => $feature->id],
                [
                    'baslik' => $feature->baslik ?? '',
                    'icon' => $feature->icon ?? '',
                    'sira' => $feature->sira ?? 0,
                ]
            );
            $count++;
        }

        $this->stats['features'] = $count;
    }

    protected function migrateAdditionalServices()
    {
        $this->info('Migrating additional services...');
        
        $legacyServices = DB::connection($this->legacyConnection)
            ->table('blm_ek_hizmetler')
            ->get();

        $count = 0;
        foreach ($legacyServices as $service) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            AdditionalService::updateOrCreate(
                ['id' => $service->id],
                [
                    'baslik' => $service->baslik ?? '',
                    'fiyat' => $service->fiyat ?? 0,
                ]
            );
            $count++;
        }

        $this->stats['services'] = $count;
    }

    protected function migratePaymentMethods()
    {
        $this->info('Migrating payment methods...');
        
        $legacyMethods = DB::connection($this->legacyConnection)
            ->table('blm_odeme_tipleri')
            ->get();

        $count = 0;
        foreach ($legacyMethods as $method) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            PaymentMethod::updateOrCreate(
                ['id' => $method->id],
                [
                    'baslik' => $method->baslik ?? '',
                ]
            );
            $count++;
        }

        $this->stats['payment_methods'] = $count;
    }

    protected function migrateVillas()
    {
        $this->info('Migrating villas...');
        
        $legacyVillas = DB::connection($this->legacyConnection)
            ->table('blm_villalar')
            ->get();

        $count = 0;
        foreach ($legacyVillas as $villa) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            // Parse JSON/serialized fields
            $teknelOzellikler = $this->parseArrayField($villa->teknik_ozellikler ?? '');
            $ekHizmetler = $this->parseArrayField($villa->ek_hizmetler ?? '');
            $mesafeBilgileri = $this->parseArrayField($villa->mesafe_bilgileri ?? '');
            $genelBilgiler = $this->parseArrayField($villa->genel_bilgiler ?? '');

            Villa::updateOrCreate(
                ['id' => $villa->id],
                [
                    'baslik' => $villa->baslik ?? '',
                    'sef' => $villa->sef ?? '',
                    'kisa_aciklama' => $villa->kisa_aciklama ?? '',
                    'icerik' => $villa->icerik ?? '',
                    'konum' => $villa->konum ?? '',
                    'gorsel' => $villa->gorsel ?? '',
                    'region_id' => $villa->bolge ?? null,
                    'villa_type_id' => $villa->villa_turu ?? null,
                    'fiyat' => $villa->fiyat ?? 0,
                    'kisi_sayisi' => $villa->kisi_sayisi ?? 0,
                    'yatak_sayisi' => $villa->yatak_sayisi ?? 0,
                    'banyo_sayisi' => $villa->banyo_sayisi ?? 0,
                    'oda_sayisi' => $villa->oda_sayisi ?? 0,
                    'minimum_kiralama_suresi' => $villa->minimum_kiralama_suresi ?? 1,
                    'teknik_ozellikler' => $teknelOzellikler,
                    'ek_hizmetler' => $ekHizmetler,
                    'mesafe_bilgileri' => $mesafeBilgileri,
                    'genel_bilgiler' => $genelBilgiler,
                    'title' => $villa->title ?? '',
                    'description' => $villa->description ?? '',
                    'keywords' => $villa->keywords ?? '',
                    'durum' => $villa->durum ?? 0,
                    'anasayfa' => $villa->anasayfa ?? 0,
                    'anasayfa_sira' => $villa->anasayfa_sira ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        $this->stats['villas'] = $count;
    }

    protected function migrateVillaImages()
    {
        $this->info('Migrating villa images...');
        
        $legacyImages = DB::connection($this->legacyConnection)
            ->table('blm_villa_resimleri')
            ->get();

        $count = 0;
        foreach ($legacyImages as $image) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            VillaImage::updateOrCreate(
                ['id' => $image->id],
                [
                    'villa_id' => $image->villa_id,
                    'bresim' => $image->bresim ?? '',
                    'kresim' => $image->kresim ?? '',
                    'sira' => $image->sira ?? 0,
                ]
            );
            $count++;
        }

        $this->stats['villa_images'] = $count;
    }

    protected function migrateVillaSeasonalPrices()
    {
        $this->info('Migrating seasonal prices...');
        
        $legacyPrices = DB::connection($this->legacyConnection)
            ->table('blm_villa_fiyat_donem')
            ->get();

        $count = 0;
        foreach ($legacyPrices as $price) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            VillaSeasonalPrice::updateOrCreate(
                ['id' => $price->id],
                [
                    'villa_id' => $price->villa_id,
                    'tarih1' => $price->tarih1,
                    'tarih2' => $price->tarih2,
                    'fiyat' => $price->fiyat ?? 0,
                ]
            );
            $count++;
        }

        $this->stats['seasonal_prices'] = $count;
    }

    protected function migrateReservations()
    {
        $this->info('Migrating reservations...');
        
        $legacyReservations = DB::connection($this->legacyConnection)
            ->table('blm_rezervasyonlar')
            ->get();

        $count = 0;
        foreach ($legacyReservations as $res) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            $ekHizmetler = $this->parseArrayField($res->ek_hizmetler ?? '');

            Reservation::updateOrCreate(
                ['id' => $res->id],
                [
                    'villa_id' => $res->villa_id,
                    'adsoyad' => $res->adsoyad ?? '',
                    'email' => $res->email ?? '',
                    'telefon' => $res->telefon ?? '',
                    'tc' => $res->tc ?? '',
                    'adres' => $res->adres ?? '',
                    'yetiskin' => $res->yetiskin ?? 0,
                    'cocuk' => $res->cocuk ?? 0,
                    'gelis_tarihi' => $res->gelis_tarihi,
                    'cikis_tarihi' => $res->cikis_tarihi,
                    'rezervasyon_tarihi' => $res->rezervasyon_tarihi,
                    'toplam_tutar' => $res->toplam_tutar ?? 0,
                    'odeme_tipi' => $res->odeme_tipi ?? '',
                    'notu' => $res->notu ?? '',
                    'durum' => $res->durum ?? 0,
                    'harici' => $res->harici ?? 0,
                    'ek_hizmetler' => $ekHizmetler,
                    'ip' => $res->ip ?? '',
                ]
            );
            $count++;
        }

        $this->stats['reservations'] = $count;
    }

    protected function migrateContacts()
    {
        $this->info('Migrating contact messages...');
        
        $legacyContacts = DB::connection($this->legacyConnection)
            ->table('blm_iletisim')
            ->get();

        $count = 0;
        foreach ($legacyContacts as $contact) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            Contact::updateOrCreate(
                ['id' => $contact->id],
                [
                    'adsoyad' => $contact->adsoyad ?? '',
                    'email' => $contact->email ?? '',
                    'telefon' => $contact->telefon ?? '',
                    'konu' => $contact->konu ?? '',
                    'mesaj' => $contact->mesaj ?? '',
                    'tarih' => $contact->tarih ?? time(),
                    'ip' => $contact->ip ?? '',
                    'durum' => $contact->durum ?? 0,
                ]
            );
            $count++;
        }

        $this->stats['contacts'] = $count;
    }

    protected function migrateNewsletters()
    {
        $this->info('Migrating newsletters...');
        
        $legacyNewsletters = DB::connection($this->legacyConnection)
            ->table('blm_newsletter')
            ->get();

        $count = 0;
        foreach ($legacyNewsletters as $newsletter) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            Newsletter::updateOrCreate(
                ['email' => $newsletter->email],
                [
                    'tarih' => $newsletter->tarih ?? '',
                ]
            );
            $count++;
        }

        $this->stats['newsletters'] = $count;
    }

    protected function copyImages()
    {
        $this->info('Copying images from legacy folder...');
        
        $sourcePath = $this->imagesPath;
        $destPath = storage_path('app/public/villas');

        if (!File::isDirectory($sourcePath)) {
            $this->warn('Source images path does not exist: ' . $sourcePath);
            return;
        }

        if (!File::isDirectory($destPath)) {
            File::makeDirectory($destPath, 0755, true);
        }

        $files = File::files($sourcePath);
        $count = 0;

        foreach ($files as $file) {
            if ($this->isDryRun) {
                $count++;
                continue;
            }

            $destFile = $destPath . '/' . $file->getFilename();
            if (!File::exists($destFile)) {
                File::copy($file->getPathname(), $destFile);
                $count++;
            }
        }

        $this->stats['images_copied'] = $count;
    }

    protected function parseArrayField($value): array
    {
        if (empty($value)) {
            return [];
        }

        // Try JSON decode first
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Try PHP unserialize
        $unserialized = @unserialize($value);
        if (is_array($unserialized)) {
            return $unserialized;
        }

        // Try comma-separated
        if (strpos($value, ',') !== false) {
            return array_filter(array_map('trim', explode(',', $value)));
        }

        return [];
    }

    protected function displayStats()
    {
        $this->newLine();
        $this->info('=== Migration Statistics ===');
        
        $headers = ['Entity', 'Count'];
        $rows = [];
        
        foreach ($this->stats as $entity => $count) {
            $rows[] = [ucfirst(str_replace('_', ' ', $entity)), $count];
        }
        
        $this->table($headers, $rows);
    }
}
