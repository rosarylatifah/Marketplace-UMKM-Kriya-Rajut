<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // --- 1. Fungsi untuk mengambil data pesanan (getData) ---
    private function getPesananData()
    {
        // Simulasi data pesanan yang masuk untuk dikelola admin
        return [
            ['id' => 'ORD-001', 'produk' => 'Tas Rajut Boboho', 'status' => 'Perlu Dikirim'],
            ['id' => 'ORD-002', 'produk' => 'Dompet Mini Rajut', 'status' => 'Selesai'],
        ];
    }

    // --- 2. Fungsi untuk menampilkan halaman dashboard admin ---
    public function dashboard()
    {
        // Memanggil fungsi getData
        $pesanan = $this->getPesananData();

        // Mengirim data ke view admin.dashboard
        return view('admin.dashboard', compact('pesanan'));
    }

    // --- Fungsi View untuk Manajemen Produk ---
    public function produk()
    {
        return view('admin.produk');
    }
}