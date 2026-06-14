<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukFoto extends Model
{
    use HasFactory;

    protected $table = 'foto_produk'; // Menghubungkan ke tabel foto_produk di database

    protected $fillable = [
        'produk_id',
        'nama_foto'
    ];

    public function produk()
    {
        // KOREKSI: Tambahkan foreign key eksplisit agar relasi baliknya aman
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}