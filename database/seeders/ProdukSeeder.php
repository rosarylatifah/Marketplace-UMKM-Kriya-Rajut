<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProdukSeeder::class,
        ]);
        
        DB::table('produk')->insert([
            [
                'nama' => 'Tas Rajut Boboho',
                'kat' => 'Tas',
                'harga' => 150000,
                'stok' => 5,
                'status' => 'Tersedia',
                'foto' => null,
                'deskripsi' => 'Tas rajut estetik buat hangout.',
            ],
            [
                'nama' => 'Topi Winter Rajut',
                'kat' => 'Aksesoris',
                'harga' => 85000,
                'stok' => 2,
                'status' => 'Tersedia',
                'foto' => null,
                'deskripsi' => 'Hangat dan nyaman dipakai.',
            ],
            [
                'nama' => 'Syal Rajut Premium',
                'kat' => 'Aksesoris',
                'harga' => 120000,
                'stok' => 0,
                'status' => 'Habis',
                'foto' => null,
                'deskripsi' => 'Bahan wol asli lembut banget.',
            ],
        ]);
    }
}