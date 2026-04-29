<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Pastiin view-nya ada di folder resources/views/pembeli/keranjang.blade.php
        return view('pembeli.keranjang');
    }

    public function katalog($category = 'Semua')
    {
        $currentCategory = ucfirst($category);
        return view('pembeli.katalog', compact('currentCategory'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                "nama" => $request->nama,
                "quantity" => $request->quantity,
                "harga" => $request->harga,
                "foto" => $request->foto
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambah!');
    }

    // --- INI FUNGSI YANG TADI KETINGGALAN, ZAR! ---
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        // Kirim data cart ke halaman checkout biar bisa di-looping tampilannya
        return view('pembeli.checkout', compact('cart'));
    }
}