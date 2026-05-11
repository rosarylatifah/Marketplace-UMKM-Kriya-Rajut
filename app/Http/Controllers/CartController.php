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

        return redirect()->back()
            ->with('success', 'Produk berhasil ditambah!');
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
        // Kalau belum ada input
        if (!$request->email && !$request->kode) {
            return redirect('/lacak-pesanan');
        }

        // Cari pesanan
        $pesanan = \App\Models\Pesanan::where('email', $request->email)
                    ->where('id_pesanan', $request->kode)
                    ->get();

        if($pesanan->isEmpty()) {

            return redirect('/lacak-pesanan')
                ->with('error', 'Pesanan tidak ditemukan. Periksa kembali email dan kode pesananmu.');
        }

        return view('pembeli.status', compact('pesanan'));
    }

    public function prosesCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        // Cek keranjang kosong
        if(empty($cart)) {

            return redirect()->route('katalog')
                ->with('error', 'Keranjang kamu kosong!');
        }

        $idPesanan = 'ORD-' . time();

        $subtotal = 0;
        $nama_barang = [];

        foreach($cart as $item) {

            $subtotal += $item['harga'] * $item['quantity'];

            $nama_barang[] = $item['nama'] . ' (x' . $item['quantity'] . ')';

            // Kurangi stok produk
            $produk = \App\Models\Produk::where('nama', $item['nama'])->first();

            if($produk) {

                $produk->stok -= $item['quantity'];

                if($produk->stok < 0) {
                    $produk->stok = 0;
                }

                $produk->save();
            }
        }

        $ongkir = $request->input('ongkir', 0);

        $total = $subtotal + $ongkir;

        // Simpan pesanan ke database
        \App\Models\Pesanan::create([
            'id_pesanan'   => $idPesanan,
            'nama_pembeli' => $request->input('nama', 'Pembeli'),
            'email'        => $request->input('email', ''),
            'nama_barang'  => implode(', ', $nama_barang),
            'total'        => $total,
            'ongkir'       => $ongkir,
            'status'       => 'SEDANG DIPROSES',
        ]);

        // Simpan info pesanan
        session(['pesanan_info' => [

            'id_pesanan'   => $idPesanan,
            'nama_pembeli' => $request->input('nama', 'Pembeli'),
            'email'        => $request->input('email', ''),
            'alamat'       => $request->input('alamat', ''),
            'nama_barang'  => implode(', ', $nama_barang),
            'total'        => $total,
            'ongkir'       => $ongkir,
            'metode'       => $request->input('metode_pembayaran', 'Transfer Bank'),

        ]]);

        session()->put('pesanan_terakhir', $cart);

        session()->put('ongkir', $ongkir);

        // Hapus cart
        session()->forget('cart');

        return redirect('/pembayaran')
            ->with('success', 'Silakan selesaikan pembayaran.');
    }
}