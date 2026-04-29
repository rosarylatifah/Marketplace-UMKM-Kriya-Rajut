<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Ini biar Laravel tau nama tabelnya 'produks' (jamak dari Produk)
    // Kalau di database kamu namanya 'produk', ganti jadi protected $table = 'produk';
    protected $table = 'produk';

    // Mass Assignment - Biar bisa input data sekaligus pake ::create()
    protected $fillable = ['nama', 'kategori', 'stok', 'harga', 'deskripsi', 'foto'];
}