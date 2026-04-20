<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembeliController extends Controller
{
    // --- INI FUNGSI UNTUK MENCETAK/AMBIL DATA (getData) ---
    private function getDataProduk()
    {
        // Data ini nanti diambil dari database, tapi untuk praktikum bisa hardcode dulu
        return [
            ['nama' => 'Tas Rajut Sakura', 'harga' => 150000, 'foto' => 'sakura.jpg'],
            ['nama' => 'Boneka Amigurumi', 'harga' => 45000, 'foto' => 'kelinci.jpg'],
        ];
    }

    // --- INI FUNGSI UNTUK MENAMPILKAN VIEW ---
    public function index()
    {
        // Memanggil fungsi getData di atas
        $produk = $this->getDataProduk();

        // Mengirim data ke view 'pembeli.home'
        return view('pembeli.home', compact('produk'));
    }
}