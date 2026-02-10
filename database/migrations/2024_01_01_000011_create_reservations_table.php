<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->string('adsoyad'); // full name
            $table->string('email')->nullable();
            $table->string('telefon')->nullable();
            $table->integer('yetiskin')->default(0); // adults
            $table->integer('cocuk')->default(0); // children
            $table->string('adres')->nullable(); // address
            $table->text('notu')->nullable(); // customer note
            $table->integer('toplam_tutar')->default(0); // total amount
            $table->date('gelis_tarihi'); // check-in date
            $table->date('cikis_tarihi'); // check-out date
            $table->date('rezervasyon_tarihi'); // booking date
            $table->string('ip')->nullable();
            $table->tinyInteger('durum')->default(0); // status: 0=pending, 1=confirmed, 2=completed, 3=cancelled
            $table->text('admin_notu')->nullable(); // admin note
            $table->string('tc')->nullable(); // Turkish ID number
            $table->tinyInteger('harici')->default(0); // external reservation
            $table->string('odeme_tipi')->nullable(); // payment type
            $table->text('ek_hizmetler')->nullable(); // additional services (JSON)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
