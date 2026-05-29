<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukVariasi extends Model
{
    use HasFactory;

    protected $table = 'produk_variasi';

    protected $fillable = [
        'produk_id',
        'ukuran',
        'warna',
        'stok',
        'harga',
    ];

    // Relasi balik ke produk utama
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}