<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->string('kelime')->nullable(); // keywords
            $table->string('kisa')->nullable(); // short desc
            $table->longText('icerik')->nullable(); // content
            $table->string('sef'); // slug
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
