<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('konu'); // subject
            $table->longText('mesaj'); // message
            $table->integer('tarih')->nullable(); // timestamp
            $table->integer('gonderen')->default(0); // sender
            $table->tinyInteger('durum')->default(0); // status: 0=unread
            $table->string('ip')->nullable();
            $table->integer('alici')->default(0); // receiver
            $table->string('dosya')->nullable(); // attachment
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
