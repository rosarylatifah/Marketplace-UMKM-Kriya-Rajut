<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Konsep PBO - Inheritance (Pewarisan):
 * Class Produk mewarisi (extends) seluruh sifat, method, dan attribute 
 * dari parent class 'Illuminate\Database\Eloquent\Model' bawaan Framework Laravel.
 */
class Produk extends Model
{
    use HasFactory;

    // Menentukan pemetaan entitas tabel database untuk objek class ini
    protected $table = 'produk';

    /**
     * Konsep PBO - Encapsulation (Enkapsulasi Data):
     * Membatasi property/attribute mana saja yang diizinkan untuk diisi secara massal
     * (Mass Assignment) demi menjaga integritas data state di dalam objek.
     */
    protected $fillable = [
        'nama', 
        'kategori', 
        'stok', 
        'harga', 
        'deskripsi', 
        'foto', 
        'ukuran', 
        'warna',
        'is_pilihan'
    ];

    /**
     * Konsep PBO - Object Relationship (1-to-Many):
     * Representasi relasi antar-objek di mana satu instance dari objek class Produk 
     * dapat memiliki keterikatan dengan banyak instansiasi objek dari class ProdukVariasi.
     */
    public function variasis()
    {
        return $this->hasMany(ProdukVariasi::class, 'produk_id');
    }

    /**
/**
     * GALERI FOTO TAMBAHAN (Murni Galeri Produk diluar Foto Varian)
     * Konsep PBO - Object Relationship (1-to-Many):
     * Satu produk bisa memiliki banyak galeri foto tambahan pendukung visual katalog.
     */
    public function fotos()
    {
        // Diarahkan ke class model foto galeri yang baru
        return $this->hasMany(ProdukFoto::class, 'produk_id', 'id');
    }
}