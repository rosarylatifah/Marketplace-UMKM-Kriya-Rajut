<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('produk_variasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            
            $table->string('ukuran')->nullable(); // Misal: M
            $table->string('warna')->nullable();  // Misal: Pink
            $table->integer('stok')->default(0);  // Stok khusus buat M + Pink
            $table->integer('harga')->default(0); // Harga spesifik tiap variasi
            $table->string('foto')->nullable();   // <-- FOTO SPESIFIK VARIASI DI SINI, ZAR!
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('produk_variasi');
    }
};