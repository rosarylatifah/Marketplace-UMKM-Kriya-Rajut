<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    use HasFactory;

    protected $table = 'foto_produk';
    protected $fillable = ['produk_id', 'nama_foto'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}