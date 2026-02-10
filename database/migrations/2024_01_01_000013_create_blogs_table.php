<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->string('kelime')->nullable(); // keywords
            $table->longText('icerik')->nullable(); // content
            $table->integer('kategori')->default(0);
            $table->integer('foto')->default(0);
            $table->integer('video')->default(0);
            $table->string('resim')->nullable(); // image
            $table->string('sef'); // slug
            $table->tinyInteger('durum')->default(1); // status
            $table->string('kisa')->nullable(); // short desc
            $table->string('tarih')->nullable(); // date string
            $table->longText('etiket')->nullable(); // tags
            $table->integer('okunma')->default(0); // read count
            $table->string('yazar')->nullable(); // author
            $table->string('ay')->nullable(); // month
            $table->tinyInteger('anasayfa')->default(0); // show on homepage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
