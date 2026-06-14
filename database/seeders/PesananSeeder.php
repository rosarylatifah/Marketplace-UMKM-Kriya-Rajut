<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan; // Panggil modelnya di sini biar rapi

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Pesanan Masuk (Budi)
        Pesanan::updateOrCreate(
            ['id_pesanan' => 'ORD-001'],
            [
                'nama_pembeli' => 'Budi Sanjaya',
                'nama_barang' => 'Cardigan Rajut',
                'total' => 200000,
                'status' => 'SEDANG DIPROSES'
            ]
        );

        // 2. Data Pesanan Masuk (Zaraa)
        Pesanan::updateOrCreate(
            ['id_pesanan' => 'ORD-002'],
            [
                'nama_pembeli' => 'Amelia Putri',
                'nama_barang' => 'Amigurumi Cat',
                'total' => 50000,
                'status' => 'SEDANG DIPROSES'
            ]
        );

        // 3. Data Pesanan Selesai (Pelanggan Baru 1)
        Pesanan::updateOrCreate(
            ['id_pesanan' => 'ORD-003'],
            [
                'nama_pembeli' => 'Citra Lestari',
                'nama_barang' => 'Bandana Bunga',
                'total' => 35000,
                'status' => 'SELESAI'
            ]
        );

        // 4. Data Pesanan Selesai (Pelanggan Baru 2)
        Pesanan::updateOrCreate(
            ['id_pesanan' => 'ORD-004'],
            [
                'nama_pembeli' => 'Dimas Pratama',
                'nama_barang' => 'Gantungan Kunci Octopus',
                'total' => 15000,
                'status' => 'SELESAI'
            ]
        );
    }
}