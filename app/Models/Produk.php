<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = ['nama', 'kategori', 'stok', 'harga', 'deskripsi', 'foto', 'ukuran', 'warna'];

    // Relasi ke Variasi (Asli)
    public function variasis()
    {
        return $this->hasMany(ProdukVariasi::class, 'produk_id');
    }

    // RELASI ALIAS: Biar di Blade pembeli, panggilah $p->fotos tetap jalan mendeteksi foto-foto dari variasi!
    public function fotos()
    {
        return $this->hasMany(ProdukVariasi::class, 'produk_id')->whereNotNull('foto');
    }
}