<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // null = belum perlu refund, 'MENUNGGU' = perlu dikonfirmasi, 'SELESAI' = sudah direfund
            $table->string('refund_status')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('refund_status');
        });
    }
};