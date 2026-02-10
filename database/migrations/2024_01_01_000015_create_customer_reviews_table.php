<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('adsoyad'); // full name
            $table->text('yorum'); // review text
            $table->date('tarih'); // date
            $table->tinyInteger('durum')->default(0); // status: 0=pending, 1=approved
            $table->foreignId('villa_id')->nullable()->constrained('villas')->onDelete('set null');
            $table->integer('temizlik')->default(5); // cleanliness rating
            $table->integer('ucret')->default(5); // price rating
            $table->integer('ulasim')->default(5); // accessibility rating
            $table->integer('servis')->default(5); // service rating
            $table->float('ortalama')->default(5); // average rating
            $table->string('telefon')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};
