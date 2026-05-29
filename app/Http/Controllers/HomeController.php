<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk; // <-- WAJIB IMPORT MODEL PRODUK LU DI SINI!

class HomeController extends Controller
{
    public function index()
    {
        // KITA AMBIL DATA ASLI DARI DATABASE!
        // Trik ->latest()->take(5)->get() ini fungsinya buat ngambil 5 produk terbaru yang di-input admin
        $produk = Produk::latest()->take(5)->get();

        // Mengirim data produk ke view 'home' (sisi pembeli)
        return view('home', compact('produk'));
    }

    public function contact()
    {
        return view('contact');
    }
}