<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');            // Label tampilan, contoh: "Tas & Wadah"
            $table->string('slug')->unique();  // Untuk URL, contoh: "tas-wadah"
            $table->string('kode')->unique();  // Disimpan di kolom produk.kategori, contoh: "TAS"
            $table->timestamps();
        });

        // Isi otomatis dengan 5 kategori yang sudah ada, biar produk lama tetap nyambung
        DB::table('kategoris')->insert([
            ['nama' => 'Pakaian',     'slug' => 'pakaian',    'kode' => 'PAKAIAN',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Aksesoris',   'slug' => 'aksesoris',  'kode' => 'AKSESORIS', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dekorasi',    'slug' => 'dekorasi',   'kode' => 'DEKORASI',  'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Amigurumi',   'slug' => 'amigurumi',  'kode' => 'AMIGURUMI', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Tas & Wadah', 'slug' => 'tas-wadah',  'kode' => 'TAS',       'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};