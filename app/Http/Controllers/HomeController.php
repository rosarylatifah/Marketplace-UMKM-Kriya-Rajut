<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk; 

class HomeController extends Controller
{
    public function index()
    {
        // Tambahkan with(['variasis', 'fotos']) di sini
        $produk = Produk::with(['variasis', 'fotos'])->latest()->take(5)->get();

        return view('home', compact('produk'));
    }

    public function contact()
    {
        return view('contact');
    }
}