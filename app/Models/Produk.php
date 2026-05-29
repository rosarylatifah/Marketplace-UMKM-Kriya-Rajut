<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Ini biar Laravel tau nama tabelnya 'produk'
    protected $table = 'produk';
    protected $fillable = ['nama', 'kategori', 'stok', 'harga', 'deskripsi', 'foto']; // Kolom 'foto' lama kita biarkan sebagai foto utama/thumbnail

    // Mass Assignment - Biar bisa input data sekaligus pake ::create()
    protected $fillable = ['nama', 'kategori', 'stok', 'harga', 'deskripsi', 'foto', 'ukuran', 'warna'];

    // ================= KODE BARU: RELASI ONE-TO-MANY KE TABEL VARIASI =================
    // Fungsi ini biar Produk bisa langsung manggil variasi-variasinya
    public function variasis()
    {
        return $this->hasMany(ProdukVariasi::class, 'produk_id');
    }
}