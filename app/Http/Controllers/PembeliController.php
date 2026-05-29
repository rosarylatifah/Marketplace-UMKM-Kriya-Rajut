<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk; // <-- WAJIB IMPORT MODEL PRODUK LU DI SINI!

class PembeliController extends Controller
{
    // --- INI FUNGSI UNTUK MENAMPILKAN VIEW KATALOG ---
    public function index()
    {
        // SEKARANG KITA AMBIL DATA ASLI DARI DATABASE BENERAN!
        // Ambil semua data produk yang di-input admin lewat database
        $produk = Produk::all();

        // Mengirim data asli database ke view 'pembeli.katalog' (atau nama file katalog lu)
        return view('pembeli.katalog', compact('produk'));
    }
}