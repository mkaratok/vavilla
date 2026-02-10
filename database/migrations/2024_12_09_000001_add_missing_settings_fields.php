<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // SEO fields
            if (!Schema::hasColumn('settings', 'title')) {
                $table->string('title')->nullable()->after('siteadi');
            }
            if (!Schema::hasColumn('settings', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('settings', 'keywords')) {
                $table->text('keywords')->nullable()->after('description');
            }
            if (!Schema::hasColumn('settings', 'google_analytics')) {
                $table->text('google_analytics')->nullable()->after('keywords');
            }
            if (!Schema::hasColumn('settings', 'maintenance_mode')) {
                $table->tinyInteger('maintenance_mode')->default(0)->after('google_analytics');
            }
            
            // Logo fields
            if (!Schema::hasColumn('settings', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('settings', 'favicon')) {
                $table->string('favicon')->nullable();
            }
            if (!Schema::hasColumn('settings', 'footer_logo')) {
                $table->string('footer_logo')->nullable();
            }
            
            // Social media
            if (!Schema::hasColumn('settings', 'pinterest')) {
                $table->string('pinterest')->nullable();
            }
            if (!Schema::hasColumn('settings', 'linkedin')) {
                $table->string('linkedin')->nullable();
            }
            if (!Schema::hasColumn('settings', 'google')) {
                $table->string('google')->nullable();
            }
            
            // Contact
            if (!Schema::hasColumn('settings', 'harita')) {
                $table->text('harita')->nullable();
            }
            
            // Recaptcha
            if (!Schema::hasColumn('settings', 'recaptcha_site_key')) {
                $table->string('recaptcha_site_key')->nullable();
            }
            if (!Schema::hasColumn('settings', 'recaptcha_secret_key')) {
                $table->string('recaptcha_secret_key')->nullable();
            }
            
            // Watermark
            if (!Schema::hasColumn('settings', 'watermark_durum')) {
                $table->tinyInteger('watermark_durum')->default(0);
            }
            if (!Schema::hasColumn('settings', 'watermark_text')) {
                $table->string('watermark_text')->nullable();
            }
            
            // Contract
            if (!Schema::hasColumn('settings', 'sozlesme')) {
                $table->longText('sozlesme')->nullable();
            }
            
            // Language
            if (!Schema::hasColumn('settings', 'varsayilan_dil')) {
                $table->string('varsayilan_dil')->default('tr');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'title', 'description', 'keywords', 'google_analytics', 'maintenance_mode',
                'logo', 'favicon', 'footer_logo',
                'pinterest', 'linkedin', 'google',
                'harita', 'recaptcha_site_key', 'recaptcha_secret_key',
                'watermark_durum', 'watermark_text', 'sozlesme'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
