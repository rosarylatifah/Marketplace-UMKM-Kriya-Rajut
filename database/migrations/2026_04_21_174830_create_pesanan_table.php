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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('id_pesanan')->unique(); // Contoh: ORD-001
            $table->string('nama_pembeli');
            $table->text('nama_barang'); // Pake text biar muat banyak kalau itemnya nambah
            $table->integer('total')->default(0);
            $table->integer('ongkir')->default(0);
            $table->string('status')->default('Perlu Dikirim');
            $table->timestamps(); // Ini kode lama lu (created_at & updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};