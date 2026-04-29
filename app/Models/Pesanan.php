<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Kasih tau Laravel kalau ini jembatan buat tabel 'pesanan'
    protected $table = 'pesanan';

    // Ini ijin biar datanya bisa diisi (Mass Assignment)
    protected $fillable = [
        'id_pesanan',
        'nama_pembeli',
        'nama_barang',
        'total',
        'status',
        'ongkir'
    ];
}