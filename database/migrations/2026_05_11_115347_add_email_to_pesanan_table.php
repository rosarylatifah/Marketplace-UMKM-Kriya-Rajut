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
        // FIX: Cek dulu secara otomatis, kalau kolom 'email' BELUM ADA, baru dibikin.
        // Kalau UDAH ADA, codingan ini bakal ngelewatin tanpa bikin error duplicate!
        if (!Schema::hasColumn('pesanan', 'email')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->string('email')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pesanan', 'email')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};