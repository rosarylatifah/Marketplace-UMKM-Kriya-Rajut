<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('keranjang');
    }

    public function katalog($category = 'Semua')
{
    // Kita capitalize supaya tampilannya rapi, misal: "pakaian" jadi "Pakaian"
    $currentCategory = ucfirst($category);
    
return view('pembeli.katalog', compact('currentCategory'));}

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
        
        // Redirect balik ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil ditambah!');
    }
}