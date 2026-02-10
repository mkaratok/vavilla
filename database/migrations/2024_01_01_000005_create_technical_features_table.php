<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_features', function (Blueprint $table) {
            $table->id();
            $table->string('baslik'); // title
            $table->tinyInteger('gosterim')->default(0); // display status
            $table->string('ikon')->nullable(); // font awesome icon
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_features');
    }
};
