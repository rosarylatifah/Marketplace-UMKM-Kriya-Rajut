<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $fillable = ['nama', 'slug', 'kode'];

    public function jumlahProduk()
    {
        return Produk::where('kategori', $this->kode)->count();
    }

    public static function buatDariNama(string $nama): array
    {
        return [
            'nama' => $nama,
            'slug' => Str::slug($nama),
            'kode' => Str::upper(Str::slug($nama, '_')),
        ];
    }
}