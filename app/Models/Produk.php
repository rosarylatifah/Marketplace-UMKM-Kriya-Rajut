<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = ['nama', 'kategori', 'stok', 'harga', 'deskripsi', 'foto']; // Kolom 'foto' lama kita biarkan sebagai foto utama/thumbnail

    // Hubungkan ke tabel foto_produk
    public function fotos()
    {
        return $this->hasMany(FotoProduk::class, 'produk_id');
    }
}