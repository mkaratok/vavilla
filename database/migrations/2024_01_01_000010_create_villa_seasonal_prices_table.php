<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_seasonal_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->date('tarih1'); // start date
            $table->date('tarih2'); // end date
            $table->integer('fiyat'); // price per night
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_seasonal_prices');
    }
};
