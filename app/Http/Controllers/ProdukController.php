<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller 
{
    /**
     * FR-09: Pelanggan dapat melihat katalog produk tanpa perlu login.
     * FR-10: Pelanggan dapat mencari produk berdasarkan keyword.
     * Alur PBO: Mengambil kumpulan objek (Collection) dari class Produk 
     * dengan menerapkan filter kategori dan pencarian string (LIKE).
     */
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
        $search = request()->query('search');

        // 🌟 SISI ADMIN (Halaman Kelola Produk + Fitur Search)
        if (request()->is('admin/*')) {
            $query_admin = Produk::with(['variasis', 'fotos']);

            // Jalankan logika search khusus Admin jika ada input keyword
            if ($search) {
                $query_admin->where(function($q) use ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                      ->orWhere('kategori', 'LIKE', "%{$search}%")
                      ->orWhere('harga', 'LIKE', "%{$search}%");
                });
            }

            $produk = $query_admin->orderBy('created_at', 'desc')->get(); 
            return view('admin.kelola_produk', compact('produk'));
        }

        // 🛒 SISI PEMBELI (KATALOG) - Query cerdas & fleksibel berdasarkan spasi kata
        $query = Produk::with(['variasis', 'fotos']);

        // 1. Kombinasi Filter Kategori
        if ($currentCategory !== 'Semua') {
            $query->where('kategori', 'LIKE', "%{$currentCategory}%");
        }

        // 2. Fitur Pencarian Multi-Kata Sisi Pembeli
        if (!empty($search)) {
            $words = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($search))));

            $query->where(function($q) use ($words) {
                foreach ($words as $word) {
                    $q->where(function($subQ) use ($word) {
                        $subQ->where('nama', 'LIKE', "%{$word}%")
                             ->orWhere('deskripsi', 'LIKE', "%{$word}%")
                             ->orWhere('kategori', 'LIKE', "%{$word}%");
                    });
                }
            });
        }

        // 3. Eksekusi ambil data hasil filter & pencarian pembeli
        $produk = $query->get();

        return view('pembeli.katalog', compact('currentCategory', 'produk'));
    }

    /**
     * Fungsi jembatan untuk mengarahkan rute katalog pembeli ke fungsi index.
     */
    public function katalog($category = null)
    {
        return $this->index($category);
    }

    /**
     * FR-10: Pelanggan dapat melihat detail produk.
     * Alur PBO: Mengambil satu objek data (instance) dari class Produk berdasarkan ID
     * beserta relasi variasinya (Eager Loading) untuk ditampilkan ke halaman detail pembeli.
     */
    public function show($id)
    {
        $produk = Produk::with('variasis')->findOrFail($id);
        return view('pembeli.detail_produk', compact('produk'));
    }

    /**
     * Menampilkan halaman form tambah produk untuk admin.
     */
    public function create()
    {
        return view('admin.create'); 
    }

    /**
     * SISI ADMIN: Menyimpan data produk baru beserta variasi dan filenya.
     */
    public function store(Request $request)
    {
        // 1. Validasi diubah: ukuran, warna, dan foto diubah jadi 'nullable' agar fleksibel
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required',
            'variasi' => 'required|array|min:1',
            'variasi.*.ukuran' => 'nullable|string', // Berubah jadi nullable
            'variasi.*.warna' => 'nullable|string',  // Berubah jadi nullable
            'variasi.*.stok' => 'required|integer|min:0',
            'variasi.*.harga' => 'required|integer|min:0',
            'variasi.*.foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Berubah jadi nullable
        ]);

        // 2. Hitung otomatis total stok & ambil harga dasar variasi pertama
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $firstKey = array_key_first($request->variasi);
        $hargaDefault = $request->variasi[$firstKey]['harga'] ?? 0;

        // 3. Upload file foto variasi yang tersedia ke public/images
        $namaFotoVarianBaru = [];
        foreach ($request->variasi as $index => $item) {
            if ($request->hasFile("variasi.{$index}.foto")) {
                $fileVarian = $request->file("variasi.{$index}.foto");
                $namaFotoVarian = time() . "_varian_{$index}." . $fileVarian->getClientOriginalExtension();
                $fileVarian->move(public_path('images'), $namaFotoVarian);
                $namaFotoVarianBaru[$index] = $namaFotoVarian;
            }
        }

        // 4. Set foto utama (jika variasi pertama ada fotonya, ambil itu. Jika tidak, set null dulu nanti di-update/pakai default asset)
        $fotoUtama = $namaFotoVarianBaru[$firstKey] ?? null;

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

        // 6. Simpan semua data variasi murni baru ke database
        foreach ($request->variasi as $index => $item) {
            $namaFotoBaru = $namaFotoVarianBaru[$index] ?? null;

            ProdukVariasi::create([
                'produk_id' => $produk->id,
                'ukuran' => $item['ukuran'] ?? null, // Fallback null jika kosong
                'warna' => $item['warna'] ?? null,   // Fallback null jika kosong
                'stok' => $item['stok'],
                'harga' => $item['harga'],
                'foto' => $namaFotoBaru,
            ]);
        }

        return redirect()->route('admin.produk.index')->with('success', 'Produk baru dan seluruh variasi berhasil ditambahkan!');
    }

    /**
     * SISI ADMIN: Menampilkan halaman form edit produk beserta variasi lamanya.
     */
    public function edit($id)
    {
        $produk = Produk::with('variasis')->findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    }

    /**
     * SISI ADMIN: Memperbarui data produk lama beserta variasi dinamisnya.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi input proses edit juga disesuaikan jadi 'nullable'
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required',
            'variasi' => 'required|array|min:1',
            'variasi.*.ukuran' => 'nullable|string', // Berubah jadi nullable
            'variasi.*.warna' => 'nullable|string',  // Berubah jadi nullable
            'variasi.*.stok' => 'required|integer|min:0',
            'variasi.*.harga' => 'required|integer|min:0',
            'variasi.*.foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $produk = Produk::findOrFail($id);

        // 2. Hitung otomatis total stok & harga default dari data modifikasi terbaru
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $firstKey = array_key_first($request->variasi);
        $hargaDefault = $request->variasi[$firstKey]['harga'] ?? 0;

        // 3. Upload semua file foto variasi baru yang masuk (Proses Sekali di Awal)
        $namaFotoVarianUploaded = [];
        foreach ($request->variasi as $index => $item) {
            if ($request->hasFile("variasi.{$index}.foto")) {
                $fileVarian = $request->file("variasi.{$index}.foto");
                $namaFotoVarian = time() . "_varian_{$index}." . $fileVarian->getClientOriginalExtension();
                $fileVarian->move(public_path('images'), $namaFotoVarian);
                $namaFotoVarianUploaded[$index] = $namaFotoVarian;
            }
        }

        // 4. Proses Sinkronisasi Relasi Variasi (Update Lama vs Bikin Baru)
        $fotoVarianPertama = null;
        $idVariasiYangDiterima = [];

        foreach ($request->variasi as $index => $item) {
            if (isset($item['id'])) {
                // UPDATE DATA VARIASI LAMA
                $varian = ProdukVariasi::findOrFail($item['id']);
                $namaFoto = $varian->foto; 
                $idVariasiYangDiterima[] = $varian->id;

                // Jika ada file foto baru diupload untuk variasi lama ini
                if (isset($namaFotoVarianUploaded[$index])) {
                    if ($varian->foto && file_exists(public_path('images/' . $varian->foto))) {
                        @unlink(public_path('images/' . $varian->foto));
                    }
                    $namaFoto = $namaFotoVarianUploaded[$index];
                }

                $varian->update([
                    'ukuran' => $item['ukuran'] ?? null,
                    'warna' => $item['warna'] ?? null,
                    'stok' => $item['stok'],
                    'harga' => $item['harga'],
                    'foto' => $namaFoto,
                ]);

                if ($index === $firstKey) {
                    $fotoVarianPertama = $namaFoto;
                }
            } else {
                // BUAT VARIASI MURNI BARU (Klik tombol + Tambah Varian di Form Edit)
                $namaFotoBaru = $namaFotoVarianUploaded[$index] ?? null;

                $varianBaru = ProdukVariasi::create([
                    'produk_id' => $produk->id,
                    'ukuran' => $item['ukuran'] ?? null,
                    'warna' => $item['warna'] ?? null,
                    'stok' => $item['stok'],
                    'harga' => $item['harga'],
                    'foto' => $namaFotoBaru,
                ]);
                
                $idVariasiYangDiterima[] = $varianBaru->id;

                if ($index === $firstKey) {
                    $fotoVarianPertama = $namaFotoBaru;
                }
            }
        }

        // Menghapus data variasi lama di DB jika admin mengklik tombol hapus variasi
        $produk->variasis()->whereNotIn('id', $idVariasiYangDiterima)->delete();

        // 5. Perbarui duplikasi foto utama produk induk pakai aset variasi ke-1 jika tersedia
        $fotoUtama = $produk->foto;
        if ($fotoVarianPertama) {
            $fotoUtama = $fotoVarianPertama;
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

        return redirect()->route('admin.produk.index')->with('success', 'Data produk dan seluruh variasi berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman home pelanggan dengan seluruh data produk.
     */
    public function home()
    {
        $produk = Produk::all(); 
        return view('pembeli.home', compact('produk')); 
    }
    
    /**
     * SISI ADMIN: Menghapus produk secara permanen beserta filenya.
     */
    public function destroy($id)
    {
        $produk = Produk::with('variasis')->findOrFail($id);

        foreach ($produk->variasis as $varian) {
            if ($varian->foto && file_exists(public_path('images/' . $varian->foto))) {
                @unlink(public_path('images/' . $varian->foto));
            }
        }

        if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
            @unlink(public_path('images/' . $produk->foto));
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus bersih!');
    }
}