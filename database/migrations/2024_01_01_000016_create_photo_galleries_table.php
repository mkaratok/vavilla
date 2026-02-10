<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->string('sef'); // slug
            $table->string('kelime')->nullable(); // keywords
            $table->string('kisa')->nullable(); // short desc
            $table->timestamps();
        });

        Schema::create('photo_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('photo_galleries')->onDelete('cascade');
            $table->string('kresim'); // small image
            $table->string('bresim'); // large image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_gallery_images');
        Schema::dropIfExists('photo_galleries');
    }
};
