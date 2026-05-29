<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\Pesanan;

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
        
        // Kita jadikan gabungan "ID_Produk-ID_Variasi" sebagai KEY unik di dalam session cart
        // Biar kalau pembeli beli produk yang sama tapi beda variasi, gak saling timpa!
        $variasiId = $request->produk_variasi_id;
        $cartKey = $request->id . '-' . $variasiId;

        // Ambil info variasi buat dapetin teks ukuran & warna
        $variasi = ProdukVariasi::find($variasiId);
        $varianInfo = $variasi ? ' (' . $variasi->ukuran . ' - ' . $variasi->warna . ')' : '';

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                "id_produk"         => $request->id,
                "produk_variasi_id" => $variasiId,
                "nama"              => $request->nama . $varianInfo, // Biar di invoice muncul nama variannya
                "quantity"          => $request->quantity,
                "harga"             => $request->harga, // Harga dinamis variasi dari hidden input view kemarin
                "foto"              => $request->foto,
                "deskripsi"         => $request->deskripsi
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()
            ->with('success', 'Produk berhasil ditambah ke keranjang!');
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
        if (!$request->email && !$request->kode) {
            return redirect('/lacak-pesanan');
        }

        $pesanan = Pesanan::where('email', $request->email)
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

        if(empty($cart)) {
            return redirect()->route('katalog')
                ->with('error', 'Keranjang kamu kosong!');
        }

        $idPesanan = 'ORD-' . time();
        $subtotal = 0;
        $nama_barang = [];

        // DB Transaction dipasang biar aman pas looping potong stok database
        \DB::transaction(function () use ($cart, &$subtotal, &$nama_barang) {
            foreach($cart as $item) {
                $subtotal += $item['harga'] * $item['quantity'];
                $nama_barang[] = $item['nama'] . ' (x' . $item['quantity'] . ')';

                // ================= LOGIKA BARU: POTONG STOK DI TABEL VARIASI =================
                if (isset($item['produk_variasi_id'])) {
                    $variasi = ProdukVariasi::find($item['produk_variasi_id']);

                    if($variasi) {
                        // Antisipasi jika pembeli checkout melebihi stok yang ada saat itu
                        if ($variasi->stok < $item['quantity']) {
                            throw new \Exception("Maaf, stok variasi untuk " . $item['nama'] . " tidak mencukupi.");
                        }

                        // Kurangi stok di tabel produk_variasis
                        $variasi->stok -= $item['quantity'];
                        $variasi->save();
                    }
                }
                // =============================================================================
            }
        });

        $ongkir = $request->input('ongkir', 0);
        $total = $subtotal + $ongkir;

        // Simpan pesanan ke database
        Pesanan::create([
            'id_pesanan'   => $idPesanan,
            'nama_pembeli' => $request->input('nama', 'Pembeli'),
            'email'        => $request->input('email', ''),
            'nama_barang'  => implode(', ', $nama_barang),
            'total'        => $total,
            'ongkir'       => $ongkir,
            'status'       => 'SEDANG DIPROSES',
        ]);

        // Simpan info pesanan ke session buat halaman pembayaran
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

        // Kosongkan keranjang setelah berhasil checkout
        session()->forget('cart');

        return redirect('/pembayaran')
            ->with('success', 'Silakan selesaikan pembayaran.');
    }
}