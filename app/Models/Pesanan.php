<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    
    protected $fillable = [
    'id_pesanan',
    'nama_pembeli',
    'email',
    'nama_barang',
    'total',
    'ongkir',
    'status',
    ];
}
