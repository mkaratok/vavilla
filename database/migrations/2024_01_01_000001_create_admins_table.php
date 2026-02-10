<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('kullanici_adi'); // username
            $table->string('sifre'); // password (md5 in legacy)
            $table->string('email');
            $table->string('tel')->nullable();
            $table->string('adsoyad'); // full name
            $table->string('songiris')->nullable(); // last login time
            $table->string('songirisip')->nullable(); // last login IP
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
