<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villas', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->string('kelime')->nullable(); // keywords
            $table->string('kisa')->nullable(); // short description
            $table->longText('icerik')->nullable(); // content
            $table->text('konum')->nullable(); // location (google map iframe)
            $table->integer('fiyat')->default(0); // price
            $table->integer('eski_fiyat')->default(0); // old price
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null'); // bolge
            $table->string('villa_tipi')->nullable(); // villa type (comma separated ids)
            $table->text('teknik_ozellikler')->nullable(); // technical features (JSON)
            $table->tinyInteger('anasayfa')->default(0); // show on homepage
            $table->tinyInteger('firsat')->default(0); // is opportunity/deal
            $table->text('mesafe_bilgileri')->nullable(); // distance info (JSON)
            $table->text('genel_bilgiler')->nullable(); // general info (JSON)
            $table->tinyInteger('durum')->default(1); // status
            $table->integer('minimum_kiralama_suresi')->default(1); // min rental days
            $table->string('gorsel')->nullable(); // main image
            $table->string('sef'); // slug
            $table->integer('adet')->default(1); // count
            $table->integer('yetiskin')->default(0); // adult capacity
            $table->integer('anasayfa_sira')->default(0); // homepage order
            $table->text('ek_hizmetler')->nullable(); // additional services (JSON)
            $table->string('adres')->nullable(); // address
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};
