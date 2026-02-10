<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('siteadi')->nullable(); // site name
            $table->string('anahtar_kelime')->nullable(); // keywords
            $table->string('aciklama')->nullable(); // description
            $table->text('site_dogrulama')->nullable(); // site verification
            $table->text('sayac_kodu')->nullable(); // counter code
            $table->string('mailsunucu')->nullable(); // mail server
            $table->string('port')->nullable();
            $table->string('email')->nullable();
            $table->string('sifre')->nullable(); // mail password
            $table->text('map')->nullable();
            $table->date('sitemap')->nullable();
            $table->string('baslik')->nullable(); // title
            $table->text('kisa')->nullable(); // short desc
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook_sayfa_adi')->nullable();
            $table->string('facebook_app_id')->nullable();
            $table->string('twitter_sayfa_adi')->nullable();
            $table->string('youtube')->nullable();
            $table->string('menu_ustu_adres')->nullable();
            $table->string('telefon1')->nullable();
            $table->string('iletisim_adres')->nullable();
            $table->string('telefon2')->nullable();
            $table->string('iletisim_email')->nullable();
            $table->tinyInteger('popup')->default(0);
            $table->longText('popup_icerik')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('sosyal_aciklama')->nullable();
            $table->text('footer_aciklama')->nullable();
            $table->text('menu')->nullable();
            $table->string('blog')->nullable();
            $table->string('ekip')->nullable();
            $table->string('hakkimizda')->nullable();
            $table->string('kariyer')->nullable();
            $table->string('uzmanlik')->nullable();
            $table->longText('kariyer_yazi')->nullable();
            $table->longText('ekip_yazi')->nullable();
            $table->text('branslar')->nullable();
            $table->string('site_anahtari')->nullable(); // reCaptcha site key
            $table->string('gizli_anahtar')->nullable(); // reCaptcha secret
            $table->tinyInteger('watermark')->default(0);
            $table->tinyInteger('dil_durum')->default(0);
            $table->tinyInteger('ingilzce')->default(0);
            $table->tinyInteger('fransa')->default(0);
            $table->tinyInteger('almanca')->default(0);
            $table->tinyInteger('italyanca')->default(0);
            $table->tinyInteger('rusca')->default(0);
            $table->tinyInteger('ispanyolca')->default(0);
            $table->tinyInteger('arapca')->default(0);
            $table->longText('sozlesme_metni_1')->nullable();
            $table->longText('sozlesme_metni_2')->nullable();
            $table->integer('on_kiralama_bedeli')->default(0);
            $table->string('hesap_no')->nullable();
            $table->string('yetkili_kisi')->nullable();
            $table->string('ileti_merkezi_username')->nullable();
            $table->string('ileti_merkezi_password')->nullable();
            $table->string('ileti_merkezi_sender')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
