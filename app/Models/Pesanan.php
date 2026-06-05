<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Konsep PBO - Inheritance (Pewarisan):
 * Class Pesanan bertindak sebagai child class yang mewarisi sifat, method, 
 * dan kapabilitas ORM (Object-Relational Mapping) dari parent class 'Model'.
 */
class Pesanan extends Model
{
    // Menentukan nama tabel database yang direpresentasikan oleh class model ini
    protected $table = 'pesanan';
    
    /**
     * Konsep PBO - Encapsulation (Enkapsulasi Properti):
     * Mendaftarkan attribute objek secara eksplisit ke dalam array fillable.
     * Langkah ini membatasi modifikasi data state secara liar dan mengamankan 
     * proses instansiasi data transaksi baru dari manipulasi pihak luar.
     */
    protected $fillable = [
        'id_pesanan',
        'nama_pembeli',
        'email',
        'nama_barang',
        'total',
        'ongkir',
        'status',
        'bukti_pembayaran',
    ];
}