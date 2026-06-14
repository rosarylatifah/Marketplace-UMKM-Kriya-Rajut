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
            $table->string('id_pesanan')->unique(); 
            $table->string('nama_pembeli');
            $table->string('email')->nullable(); // ---> TAMBAHIN INI
            $table->string('no_hp')->nullable(); // ---> TAMBAHIN INI (Buat simpen No WA)
            $table->text('nama_barang'); 
            $table->integer('total')->default(0);
            $table->integer('ongkir')->default(0);
            $table->string('status')->default('Perlu Dikirim');
            $table->timestamps(); 
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