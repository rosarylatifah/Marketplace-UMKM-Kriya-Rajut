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

        // SISI ADMIN
        if (request()->is('admin/*')) {
            $produk = Produk::with(['variasis', 'fotos'])->get(); 
            return view('admin.kelola_produk', compact('produk'));
        }

        // SISI PEMBELI (KATALOG) - Query cerdas & fleksibel
        $query = Produk::with(['variasis', 'fotos']);

        // 1. Ambil keyword pencarian (jika ada)
        $keyword = request()->search;

        // 2. Kombinasi Filter Kategori & Pencarian Relevan
        if ($currentCategory !== 'Semua') {
            $query->where('kategori', 'LIKE', "%{$currentCategory}%");
        }

        // 3. FITUR PENCARIAN RELEVAN (Mencari kemiripan kata di nama, deskripsi, atau kategori)
        if (!empty($keyword)) {
            $words = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($keyword))));

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

        // 4. Eksekusi ambil data hasil filter & pencarian
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
     * Alur PBO: Enkapsulasi data input melalui class Request, validasi, 
     * instansiasi objek model Produk, dan penyimpanan massal (Mass Assignment).
     */
    public function store(Request $request)
    {
        // 1. Validasi input umum & array variasi baru
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

        // 2. Hitung otomatis total stok & ambil harga dasar variasi pertama
        $totalStok = 0;
        foreach ($request->variasi as $v) {
            $totalStok += (int) $v['stok'];
        }
        $firstKey = array_key_first($request->variasi);
        $hargaDefault = $request->variasi[$firstKey]['harga'] ?? 0;

        // 3. Upload semua foto variasi secara berurutan ke public/images (Proses Sekali)
        $namaFotoVarianBaru = [];
        foreach ($request->variasi as $index => $item) {
            if ($request->hasFile("variasi.{$index}.foto")) {
                $fileVarian = $request->file("variasi.{$index}.foto");
                $namaFotoVarian = time() . "_varian_{$index}." . $fileVarian->getClientOriginalExtension();
                $fileVarian->move(public_path('images'), $namaFotoVarian);
                $namaFotoVarianBaru[$index] = $namaFotoVarian;
            }
        }

        // 4. Ambil foto dari variasi urutan pertama buat dijadikan thumbnail produk induk
        $fotoUtama = null;
        if (isset($namaFotoVarianBaru[$firstKey])) {
            $fotoUtama = time() . '_utama.' . pathinfo($namaFotoVarianBaru[$firstKey], PATHINFO_EXTENSION);
            copy(public_path('images/' . $namaFotoVarianBaru[$firstKey]), public_path('images/' . $fotoUtama));
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

        // 6. Simpan semua data variasi murni baru ke database
        foreach ($request->variasi as $index => $item) {
            $namaFotoBaru = $namaFotoVarianBaru[$index] ?? null;

            ProdukVariasi::create([
                'produk_id' => $produk->id,
                'ukuran' => $item['ukuran'],
                'warna' => $item['warna'],
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
        // 1. Validasi input (foto dibuat nullable/opsional saat proses edit)
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
                    'ukuran' => $item['ukuran'],
                    'warna' => $item['warna'],
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
                    'ukuran' => $item['ukuran'],
                    'warna' => $item['warna'],
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

        // Menghapus data variasi lama di DB jika admin mengklik tombol trash-can di view
        $produk->variasis()->whereNotIn('id', $idVariasiYangDiterima)->delete();

        // 5. Perbarui duplikasi foto utama produk induk pakai aset variasi ke-1
        $fotoUtama = $produk->foto;
        if ($fotoVarianPertama) {
            if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
                @unlink(public_path('images/' . $produk->foto));
            }
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