<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\Pesanan;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja pelanggan.
     * Alur PBO: Mengambil representasi state object data keranjang 
     * yang disimpan sementara di dalam Session State.
     */
    public function index()
    {
        return view('pembeli.keranjang');
    }

    /**
     * FR-11: Pelanggan dapat menambahkan produk ke dalam keranjang belanja.
     * Alur PBO: Menerima kumpulan data dari HTTP Request (Encapsulation), 
     * menyusunnya menjadi array terstruktur dengan key unik gabungan ID Produk & Variasi,
     * kemudian menyimpannya ke dalam Session array tanpa persistent DB storage terlebih dahulu.
     */
public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nama' => 'required',
            'harga' => 'required',
            'quantity' => 'required|integer|min:1',
            'produk_variasi_id' => 'required'
        ]);

        $cart = session()->get('cart', []);
        
        $variasiId = $request->produk_variasi_id;
        $cartKey = $request->id . '-' . $variasiId;

        $variasi = ProdukVariasi::find($variasiId);
        $varianParts = [];
        
        if ($variasi) {
            if (!empty($variasi->ukuran)) {
                $varianParts[] = $variasi->ukuran;
            }
            if (!empty($variasi->warna)) {
                $varianParts[] = $variasi->warna;
            }
        }
        $varianInfo = !empty($varianParts) ? ' (' . implode(' - ', $varianParts) . ')' : '';

        $produk = Produk::find($request->id);
        $deskripsi = $produk ? $produk->deskripsi : 'Tidak ada deskripsi.';

        // ✨ PERBAIKAN FOTO VARIASI: 
        // Cek apakah variasi ini punya foto khusus di database. 
        // Jika tidak ada/null, otomatis fallback (kembali) ke foto utama produk dari request.
        $fotoProduk = ($variasi && !empty($variasi->foto)) ? $variasi->foto : ($produk ? $produk->foto : '');

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                "id_produk"         => $request->id,
                "produk_variasi_id" => $variasiId,
                "nama"              => $request->nama . $varianInfo, 
                "quantity"          => $request->quantity,
                "harga"             => $request->harga, 
                "foto"              => $fotoProduk, // ✨ Sekarang menggunakan foto yang dinamis!
                "deskripsi"         => $deskripsi
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()
            ->with('success', 'Produk berhasil ditambah ke keranjang!');
    }
    
    /**
     * Menampilkan halaman formulir checkout data diri.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        return view('pembeli.checkout', compact('cart'));
    }

    /**
     * Memperbarui kuantitas produk yang ada di dalam keranjang belanja via AJAX Request.
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    /**
     * Menghapus salah satu item produk dari keranjang belanja via AJAX Request.
     */
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

    /**
     * FR-12: Pelanggan dapat melakukan proses checkout dengan mengisi data diri.
     */
    public function prosesCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if(empty($cart)) {
            return redirect()->route('katalog')
                ->with('error', 'Keranjang kamu kosong!');
        }

        // Validasi input data diri pelanggan beserta opsi pengantaran baru se-Batam
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'opsi_pengantaran' => 'required|string|in:kurir_lokal,ambil_sendiri,custom_shipment',
            'metode_pembayaran' => 'required|string',
        ]);

        $idPesanan = 'ORD-' . time();
        $subtotal = 0;
        $nama_barang = [];

        // DB Transaction dipasang biar aman pas looping potong stok database (Atomicity)
        try {
            \DB::transaction(function () use ($cart, &$subtotal, &$nama_barang) {
                foreach($cart as $item) {
                    $subtotal += $item['harga'] * $item['quantity'];
                    $nama_barang[] = $item['nama'] . ' (x' . $item['quantity'] . ')';

                    // ================= PROSES PBO: POTONG STOK DI TABEL VARIASI =================
                    if (isset($item['produk_variasi_id'])) {
                        $variasi = ProdukVariasi::find($item['produk_variasi_id']);

                        if($variasi) {
                            if ($variasi->stok < $item['quantity']) {
                                throw new \Exception("Maaf, stok variasi untuk " . $item['nama'] . " tidak mencukupi.");
                            }

                            $variasi->stok -= $item['quantity'];
                            $variasi->save();
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('pembeli.keranjang')->with('error', $e->getMessage());
        }

        // ================= LOGIKA AMAN PENENTUAN ONGKIR (IDE 2) =================
        $opsi = $request->input('opsi_pengantaran');
        $ongkir = 0;

        if ($opsi === 'kurir_lokal') {
            $ongkir = 10000; // Flat Rp 10.000 se-Batam
        } elseif ($opsi === 'ambil_sendiri' || $opsi === 'custom_shipment') {
            $ongkir = 0;
        }

        $total = $subtotal + $ongkir;
        
        // Instansiasi mass assignment data baru ke tabel 'pesanan'
        Pesanan::create([
            'id_pesanan'   => $idPesanan,
            'nama_pembeli' => $request->input('nama'),
            'email'        => $request->input('email'),
            'no_hp'        => $request->input('telepon'),
            'alamat'       => $request->input('alamat'),
            'nama_barang'  => implode(', ', $nama_barang),
            'total'        => $total,
            'ongkir'       => $ongkir,
            'status'       => 'BELUM KONFIRMASI', 
            'items_snapshot' => $cart, 
        ]);

        // Simpan info invoice pesanan ke session buat halaman konfirmasi pembayaran
        session(['pesanan_info' => [
            'id_pesanan'   => $idPesanan,
            'nama_pembeli' => $request->input('nama'),
            'email'        => $request->input('email'),
            'no_hp'        => $request->input('telepon'), // Pastikan nama input form-nya 'telepon'
            'alamat'       => $request->input('alamat'),
            'nama_barang'  => implode(', ', $nama_barang),
            'total'        => $total,
            'ongkir'       => $ongkir,
            'opsi_kirim'   => $opsi,
            'metode'       => $request->input('metode_pembayaran', 'Transfer Bank'),
        ]]);

        session()->put('pesanan_terakhir', $cart);
        session()->put('ongkir', $ongkir);

        // State Management: Kosongkan data di dalam session keranjang setelah berhasil checkout
        session()->forget('cart');

        return redirect('/pembayaran')
            ->with('success', 'Silakan selesaikan pembayaran.');
    }


/**
 * SISI PEMBELI: Mengajukan pembatalan pesanan (belum benar-benar dibatalkan).
 * Menunggu persetujuan admin sebelum status berubah jadi DIBATALKAN.
 */
public function ajukanPembatalan(Request $request)
{
    $idPesanan = $request->input('id_pesanan');

    $pesanan = Pesanan::where('id_pesanan', $idPesanan)->first();

    if (!$pesanan) {
        return redirect()->back()->with('error', 'Data pesanan tidak ditemukan.');
    }

    if ($pesanan->status !== 'BELUM KONFIRMASI') {
        return redirect()->back()->with('error', 'Pesanan tidak dapat diajukan pembatalan karena sudah diproses lebih lanjut oleh admin.');
    }

    $pesanan->status = 'PENGAJUAN BATAL';
    $pesanan->save();

    return redirect()->back()->with('success', 'Pengajuan pembatalan terkirim. Admin akan menghubungimu untuk konfirmasi lebih lanjut.');
}
}