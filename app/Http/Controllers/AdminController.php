<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
// WAJIB panggil Model Pesanan biar bisa ambil data dari database
use App\Models\Pesanan; 

class AdminController extends Controller
{
    public function dashboard()
    {
        // Ambil semua data dari database
        $pesanan = Pesanan::all();
        return view('admin.dashboard', compact('pesanan'));
    }

    public function pesananMasuk()
    {
        // Ambil data dari database yang statusnya 'PERLU DIKIRIM'
        $pesanan = Pesanan::where('status', 'PERLU DIKIRIM')->get();
        return view('admin.pesanan_masuk', compact('pesanan'));
    }

    public function pesananSelesai()
    {
        // Ambil data dari database yang statusnya 'Selesai'
        $pesanan = Pesanan::where('status', 'Selesai')->get();
        return view('admin.pesanan_selesai', compact('pesanan'));
    }

    public function produk()
    {
        return view('admin.produk');
    }
}