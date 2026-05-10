<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
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
                "foto" => $request->foto,
                "deskripsi" => $request->deskripsi
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambah!');
    }

public function checkout()
    {
        $cart = session()->get('cart', []);
        return view('pembeli.checkout', compact('cart'));
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }

public function statusPesanan(Request $request)
    {
        // Mengambil data dari 'pesanan_terakhir' yang kita simpan permanen di session
        // Jika tidak ada, baru ambil dari cart (yang biasanya kosong setelah checkout)
        $pesanan = session()->get('pesanan_terakhir', session()->get('cart', []));
        
        return view('pembeli.status', compact('pesanan'));
    }

    public function prosesCheckout(Request $request) 
    {
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('katalog')->with('error', 'Keranjang kamu kosong!');
        }

        session()->put('pesanan_terakhir', $cart);
        session()->put('ongkir', $request->input('ongkir', 0));
        session()->forget('cart');
        session(['ongkir' => $request->ongkir]);
        return redirect('/pembayaran')->with('success', 'Silakan selesaikan pembayaran.');
        
    }
}