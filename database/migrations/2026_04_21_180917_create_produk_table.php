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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori'); // Gue saranin pake 'kategori' biar sinkron sama form
            $table->integer('stok');    // TAMBAHIN INI
            $table->integer('harga');
            $table->text('deskripsi')->nullable(); // TAMBAHIN INI
            $table->string('foto');    // TAMBAHIN INI
            $table->timestamps();
        });
    }
};
