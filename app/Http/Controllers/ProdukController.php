<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller 
{
    // Halaman utama kelola produk admin & katalog pembeli
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

        // SISI ADMIN
        if (request()->is('admin/*')) {
            $produk = Produk::with(['variasis', 'fotos'])->get(); // Tambah with biar admin ga ngebug
            return view('admin.kelola_produk', compact('produk'));
        }

        // SISI PEMBELI (KATALOG)
        if ($currentCategory == 'Semua') {
            // Ditambahkan with() biar foto-foto slide pembeli muncul
            $produk = Produk::with(['variasis', 'fotos'])->get(); 
        } else {
            // Ditambahkan with() biar foto-foto slide pembeli muncul
            $produk = Produk::with(['variasis', 'fotos'])->where('kategori', strtoupper($currentCategory))->get();
        }

        return view('pembeli.katalog', compact('currentCategory', 'produk'));
    }

    // Fungsi jembatan biar halaman katalog pembeli ga error
    public function katalog($category = null)
    {
        return $this->index($category);
    }

    public function create()
    {
        return view('admin.create'); 
    }

    // ================= LOGIKA STORE PRO (FOTO MASUK DI TIAP VARIASI) =================
    public function store(Request $request)
    {
        // 1. Validasi input umum & array variasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required',
            'variasi' => 'required|array|min:1',
            'variasi.*.ukuran' => 'required|string',
            'variasi.*.warna' => 'required|string',
            'variasi.*.stok' => 'required|integer|min:0',
            'variasi.*.harga' => 'required|integer|min:0',
            'variasi.*.foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Hitung otomatis total stok & ambil harga dasar dari variasi pertama
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $hargaDefault = $request->variasi[0]['harga'] ?? 0;

        // 3. Simpan dulu data variasi & upload semua fotonya secara berurutan
        $namaFotoVarianBaru = [];
        foreach ($request->variasi as $index => $item) {
            if ($request->hasFile("variasi.{$index}.foto")) {
                $fileVarian = $request->file("variasi.{$index}.foto");
                $namaFotoVarian = time() . "_varian_{$index}." . $fileVarian->getClientOriginalExtension();
                
                // Pindahkan file asli ke folder public/images
                $fileVarian->move(public_path('images'), $namaFotoVarian);
                
                // Simpan nama filenya ke array buat kita pakai nanti
                $namaFotoVarianBaru[$index] = $namaFotoVarian;
            }
        }

        // 4. Ambil foto dari variasi pertama buat dijadikan thumbnail utama produk induk
        $fotoUtama = null;
        if (isset($namaFotoVarianBaru[0])) {
            $fotoUtama = time() . '_utama.' . pathinfo($namaFotoVarianBaru[0], PATHINFO_EXTENSION);
            copy(public_path('images/' . $namaFotoVarianBaru[0]), public_path('images/' . $fotoUtama));
        }

        // 5. Simpan identitas produk induk ke tabel 'produk'
        $produk = Produk::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $totalStok, 
            'harga' => $hargaDefault, 
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoUtama,
            'ukuran' => null, 
            'warna' => null,  
        ]);

        // 6. Sekarang baru kita simpan data variasinya ke database
        foreach ($request->variasi as $index => $item) {
            ProdukVariasi::create([
                'produk_id' => $produk->id,
                'ukuran' => $item['ukuran'],
                'warna' => $item['warna'],
                'stok' => $item['stok'],
                'harga' => $item['harga'],
                'foto' => $namaFotoVarianBaru[$index] ?? null,
            ]);
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk dan Variasi berfoto berhasil disimpan, Zar!');
    }

    public function edit($id)
    {
        // Pastikan relasi variasis ikut dipanggil biar form edit bisa nampilin variasi lamanya
        $produk = Produk::with('variasis')->findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    }

    // ================= LOGIKA UPDATE PRO (SINKRONISASI VARIASI & FOTO) =================
    public function update(Request $request, $id)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required',
            'variasi' => 'required|array|min:1',
            'variasi.*.ukuran' => 'required|string',
            'variasi.*.warna' => 'required|string',
            'variasi.*.stok' => 'required|integer|min:0',
            'variasi.*.harga' => 'required|integer|min:0',
            'variasi.*.foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $produk = Produk::findOrFail($id);

        // 2. Kumpulkan ID variasi yang dikirim dari form biar tau mana yang dipertahankan
        $idVariasiForm = [];
        foreach ($request->variasi as $item) {
            if (isset($item['id'])) {
                $idVariasiForm[] = $item['id'];
            }
        }

        // 3. Cari dan Hapus variasi di DB yang GAK dikirim dari form (variasi yang dihapus admin lewat tombol trash)
        $variasiDihapus = ProdukVariasi::where('produk_id', $produk->id)->whereNotIn('id', $idVariasiForm)->get();
        foreach ($variasiDihapus as $vd) {
            if ($vd->foto && file_exists(public_path('images/' . $vd->foto))) {
                @unlink(public_path('images/' . $vd->foto)); // Hapus file fisiknya
            }
            $vd->delete(); // Hapus data di DB
        }

        // 4. Proses Update atau Create Variasi satu-per-satu
        $totalStok = 0;
        $hargaDefault = 0;
        $fotoVarianPertama = null;

        foreach ($request->variasi as $index => $item) {
            $totalStok += (int) $item['stok'];
            if ($index === array_key_first($request->variasi)) {
                $hargaDefault = $item['harga'];
            }

            // Cek apakah variasi ini data lama atau baru dibuat
            if (isset($item['id'])) {
                // UPDATE VARIASI LAMA
                $varian = ProdukVariasi::findOrFail($item['id']);
                $namaFoto = $varian->foto; // Ambil nama foto lama dulu sebagai cadangan

                if ($request->hasFile("variasi.{$index}.foto")) {
                    // Hapus foto lama kalau ada gantinya
                    if ($varian->foto && file_exists(public_path('images/' . $varian->foto))) {
                        @unlink(public_path('images/' . $varian->foto));
                    }
                    // Upload foto baru
                    $file = $request->file("variasi.{$index}.foto");
                    $namaFoto = time() . "_varian_{$index}." . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $namaFoto);
                }

                $varian->update([
                    'ukuran' => $item['ukuran'],
                    'warna' => $item['warna'],
                    'stok' => $item['stok'],
                    'harga' => $item['harga'],
                    'foto' => $namaFoto,
                ]);

                if ($index === array_key_first($request->variasi)) {
                    $fotoVarianPertama = $namaFoto;
                }
            } else {
                // BUAT VARIASI BARU (Jika admin klik + Tambah Varian pas edit)
                $namaFotoBaru = null;
                if ($request->hasFile("variasi.{$index}.foto")) {
                    $file = $request->file("variasi.{$index}.foto");
                    $namaFotoBaru = time() . "_varian_{$index}." . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $namaFotoBaru);
                }

                $varianBaru = ProdukVariasi::create([
                    'produk_id' => $produk->id,
                    'ukuran' => $item['ukuran'],
                    'warna' => $item['warna'],
                    'stok' => $item['stok'],
                    'harga' => $item['harga'],
                    'foto' => $namaFotoBaru,
                ]);

                if ($index === array_key_first($request->variasi)) {
                    $fotoVarianPertama = $namaFotoBaru;
                }
            }
        }

        // 5. Update foto utama produk induk pake foto variasi urutan pertama
        $fotoUtama = $produk->foto;
        if ($fotoVarianPertama) {
            // Hapus foto utama lama biar ga menuhin memori
            if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
                @unlink(public_path('images/' . $produk->foto));
            }
            // Duplikat foto varian pertama buat jadi foto utama produk induk
            $fotoUtama = time() . '_utama.' . pathinfo($fotoVarianPertama, PATHINFO_EXTENSION);
            copy(public_path('images/' . $fotoVarianPertama), public_path('images/' . $fotoUtama));
        }

        // 6. Update data produk induk di tabel 'produk'
        $produk->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $totalStok,
            'harga' => $hargaDefault,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoUtama,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Data produk dan seluruh variasi berhasil diperbarui, Zar!');
    }

    public function home()
    {
        $produk = Produk::all(); 
        return view('pembeli.home', compact('produk')); 
    }
    
    // ================= FUNGSI HAPUS DATA PRODUK =================
    public function destroy($id)
    {
        // 1. Cari data produk yang mau dihapus beserta variasinya
        $produk = Produk::with('variasis')->findOrFail($id);

        // 2. Hapus file foto variasi secara fisik dari folder public/images biar ga menuhin laptop
        foreach ($produk->variasis as $varian) {
            if ($varian->foto && file_exists(public_path('images/' . $varian->foto))) {
                @unlink(public_path('images/' . $varian->foto));
            }
        }

        // 3. Hapus file foto utama produk secara fisik
        if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
            @unlink(public_path('images/' . $produk->foto));
        }

        // 4. Hapus data dari database (Variasi otomatis ikut terhapus karena onDelete('cascade') di migration)
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus bersih, Zar!');
    }
}