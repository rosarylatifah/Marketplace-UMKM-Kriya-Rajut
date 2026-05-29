<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use Illuminate\Http\Request;

class ProdukController extends Controller 
{
    public function index($category = null)
    {
        $categories = [
            'semua'     => 'Semua',
            'pakaian'   => 'Pakaian',
            'aksesoris' => 'Aksesoris',
            'dekorasi'  => 'Dekorasi',
            'amigurumi' => 'Amigurumi',
            'tas-wadah' => 'Tas & Wadah'
        ];

        $currentCategory = $categories[$category] ?? 'Semua';

        if (request()->is('admin/*')) {
            $produk = \App\Models\Produk::all();
            return view('admin.kelola_produk', compact('produk'));
        }

        // Ambil dari database
        if ($currentCategory == 'Semua') {
            $produk = \App\Models\Produk::all();
        } else {
            $produk = \App\Models\Produk::where('kategori', strtoupper($currentCategory))->get();
        }

        return view('pembeli.katalog', compact('currentCategory', 'produk'));
    }

    public function create()
    {
        return view('admin.create'); 
    }

    // ================= LOGIKA STORE PRO (DUA TABEL + HARGA VARIASI) =================
    public function store(Request $request)
    {
        // 1. Validasi murni tanpa 'harga' biasa
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'variasi' => 'required|array|min:1', 
        ]);

        // 2. Proses upload file foto produk (pake gaya penamaan file lu)
        $nama_foto = time().'.'.$request->foto->extension();  
        $request->foto->move(public_path('images'), $nama_foto);

        // 3. Hitung total stok otomatis & ambil harga variasi pertama sebagai harga dasar
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $hargaDefault = $request->variasi[0]['harga'] ?? 0; // Buat pajangan di katalog luar sebelum di-klik detail

        // 4. Simpan identitas umum ke tabel 'produk' utama
        $produk = Produk::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $totalStok, // Otomatis terisi total gabungan variasi
            'harga' => $hargaDefault, // Terisi harga default dari variasi pertama
            'deskripsi' => $request->deskripsi,
            'foto' => $nama_foto,
            'ukuran' => null, // Kita set null karena datanya pindah ke tabel variasi
            'warna' => null,  // Kita set null karena datanya pindah ke tabel variasi
        ]);

        // 5. Looping buat simpan data variasi + harga spesifik ke tabel anak ('produk_variasi')
        foreach ($request->variasi as $item) {
            ProdukVariasi::create([
                'produk_id' => $produk->id, // Mengikat ke ID produk utama yang baru aja kebuat di atas
                'ukuran' => $item['ukuran'],
                'warna' => $item['warna'],
                'stok' => $item['stok'],
                'harga' => $item['harga'], // <-- Menyimpan harga masing-masing variasi secara pro!
            ]);
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk dan Variasi berharga berhasil ditambah!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // 1. Validasi input umum & array variasi (tanpa harga/stok tunggal)
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'variasi' => 'required|array|min:1',
        ]);

        // 2. Logic upload foto kalau diganti
        if ($request->hasFile('foto')) {
            if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
                unlink(public_path('images/' . $produk->foto));
            }
            $nama_foto = time().'.'.$request->foto->extension();  
            $request->foto->move(public_path('images'), $nama_foto);
            $produk->foto = $nama_foto;
        }

        // 3. Hitung ulang total stok otomatis & ambil harga dasar terbaru dari baris pertama variasi
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $hargaDefault = $request->variasi[0]['harga'] ?? 0;

        // 4. Update data produk induk
        $produk->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $totalStok, 
            'harga' => $hargaDefault,
            'deskripsi' => $request->deskripsi,
            'foto' => $produk->foto,
            'ukuran' => null, 
            'warna' => null,   
        ]);

        // 5. SELESAIKAN DATA ANAK: Hapus variasi lama, lalu tulis ulang dengan variasi baru yang diedit
        $produk->variasis()->delete(); 

        foreach ($request->variasi as $item) {
            \App\Models\ProdukVariasi::create([
                'produk_id' => $produk->id,
                'ukuran' => $item['ukuran'],
                'warna' => $item['warna'],
                'stok' => $item['stok'],
                'harga' => $item['harga'],
            ]);
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui dengan variasi baru, Zar!');
    }

    public function home()
    {
        $produk = \App\Models\Produk::latest()->take(10)->get();
        return view('pembeli.home', compact('produk'));
    }
}