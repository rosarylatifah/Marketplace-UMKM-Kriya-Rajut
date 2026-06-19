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
        Schema::table('pesanan', function (Blueprint $table) {
            // Menambahkan kolom bukti_pembayaran setelah kolom status (atau hapus ->after() kalau ragu)
            $table->string('bukti_pembayaran')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Menghapus kembali kolom jika dilakukan rollback
            $table->dropColumn('bukti_pembayaran');
        });
    }

    
};