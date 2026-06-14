<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
// Pemanggilan objek Model Pesanan untuk berinteraksi dengan database
use App\Models\Pesanan; 

class AdminController extends Controller
{
    /**
     * SISI ADMIN: Menampilkan ringkasan atau metrik performa toko di Dashboard.
     * Alur PBO: Mengambil kumpulan (Collection) seluruh objek dari class Pesanan 
     * untuk dihitung jumlah datanya (State Measurement) dan diparsing ke halaman View.
     */
    public function dashboard()
    {
        // Ambil semua instansiasi objek pesanan dari database
        $pesanan = Pesanan::all();
        return view('admin.dashboard', compact('pesanan'));
    }

    /**
     * SISI ADMIN: Menampilkan halaman utama manajemen atau kelola produk.
     */
    public function produk()
    {
        return view('admin.produk');
    }
}