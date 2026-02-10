<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->integer('kategori')->default(0); // category
            $table->string('kelime')->nullable(); // keywords
            $table->string('kisa')->nullable(); // short desc
            $table->longText('icerik')->nullable(); // content
            $table->string('sef'); // slug
            $table->integer('galeri')->default(0);
            $table->string('ust_resim')->nullable(); // header image
            $table->string('kutu_resim')->nullable(); // box image
            $table->integer('sira')->default(0); // order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
