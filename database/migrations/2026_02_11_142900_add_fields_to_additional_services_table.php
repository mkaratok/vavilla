<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('additional_services', function (Blueprint $table) {
            $table->decimal('fiyat', 10, 2)->nullable()->after('baslik');
            $table->text('aciklama')->nullable()->after('fiyat');
            $table->string('ikon')->nullable()->after('aciklama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('additional_services', function (Blueprint $table) {
            $table->dropColumn(['fiyat', 'aciklama', 'ikon']);
        });
    }
};
