<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained('villas')->onDelete('cascade');
            $table->string('bresim'); // large image
            $table->string('kresim'); // small/thumbnail image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_images');
    }
};
