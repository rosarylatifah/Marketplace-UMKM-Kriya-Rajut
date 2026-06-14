<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukVariasi;
use App\Models\ProdukFoto;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // =====================================================================
    // PEMBELI: Halaman Home
    // =====================================================================
    public function home()
    {
        $produk = Produk::with('variasis')->latest()->take(8)->get();
        return view('pembeli.home', compact('produk'));
    }

    // =====================================================================
    // PEMBELI: Katalog
    // =====================================================================
    public function katalog(Request $request, $category = null)
    {
        $query = Produk::with('variasis');

        if ($category) {
            $query->where('kategori', strtoupper($category));
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('kategori', 'like', "%$keyword%")
                  ->orWhere('deskripsi', 'like', "%$keyword%");
            });
        }

        $produk = $query->latest()->get();
        return view('pembeli.katalog', compact('produk'));
    }

    // =====================================================================
    // PEMBELI: Detail Produk
    // =====================================================================
    public function show($id)
    {
        $produk = Produk::with(['variasis', 'fotos'])->findOrFail($id);
        return view('pembeli.detail_popup', compact('produk'));
    }

    // =====================================================================
    // ADMIN: Index / Kelola Produk
    // =====================================================================
    public function index(Request $request)
    {
        $query = Produk::with('variasis');

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('kategori', 'like', "%$keyword%");
            });
        }

        $produk = $query->latest()->get();
        return view('admin.kelola_produk', compact('produk'));
    }

    // =====================================================================
    // ADMIN: Form Tambah Produk
    // =====================================================================
    public function create()
    {
        return view('admin.create');
    }

    // =====================================================================
    // ADMIN: Simpan Produk Baru
    // Perubahan:
    // - foto_display   : WAJIB
    // - deskripsi      : WAJIB
    // - kategori       : WAJIB
    // - variasi[foto]  : OPSIONAL (tidak wajib per variasi)
    // - foto_galeri    : OPSIONAL
    // =====================================================================
    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'kategori'          => 'required|string',
            'deskripsi'         => 'required|string',
            'foto_display'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variasi'           => 'required|array|min:1',
            'variasi.*.stok'    => 'required|integer|min:0',
            'variasi.*.harga'   => 'required|integer|min:0',
            // foto per variasi: OPSIONAL
            'variasi.*.foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // galeri: OPSIONAL
            'foto_galeri.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required'          => 'Nama produk wajib diisi.',
            'kategori.required'      => 'Kategori wajib dipilih.',
            'deskripsi.required'     => 'Deskripsi produk wajib diisi.',
            'foto_display.required'  => 'Foto display utama wajib diupload.',
            'foto_display.image'     => 'Foto display harus berupa gambar.',
            'variasi.required'       => 'Minimal 1 variasi harus diisi.',
            'variasi.*.stok.required'  => 'Stok tiap variasi wajib diisi.',
            'variasi.*.harga.required' => 'Harga tiap variasi wajib diisi.',
        ]);

        // --- Simpan foto display utama ---
        $namaFotoDisplay = time() . '_display_' . $request->file('foto_display')->getClientOriginalName();
        $request->file('foto_display')->move(public_path('images'), $namaFotoDisplay);

        // Ambil harga terendah dari variasi untuk kolom harga utama produk
        $hargaTerendah = collect($request->variasi)->min('harga') ?? 0;
        $totalStok     = collect($request->variasi)->sum('stok');

        // --- Buat record Produk ---
        $produk = Produk::create([
            'nama'      => $request->nama,
            'kategori'  => strtoupper($request->kategori),
            'deskripsi' => $request->deskripsi,
            'foto'      => $namaFotoDisplay,
            'harga'     => $hargaTerendah,
            'stok'      => $totalStok,
        ]);

        // --- Simpan tiap baris Variasi ---
        foreach ($request->variasi as $v) {
            $namaFotoVariasi = null;

            // Foto variasi OPSIONAL — hanya proses kalau ada file
            if (!empty($v['foto']) && $v['foto'] instanceof \Illuminate\Http\UploadedFile) {
                $namaFotoVariasi = time() . '_var_' . $v['foto']->getClientOriginalName();
                $v['foto']->move(public_path('images'), $namaFotoVariasi);
            }

            ProdukVariasi::create([
                'produk_id' => $produk->id,
                'ukuran'    => $v['ukuran'] ?? null,
                'warna'     => $v['warna'] ?? null,
                'stok'      => $v['stok'],
                'harga'     => $v['harga'],
                'foto'      => $namaFotoVariasi,
            ]);
        }

        // --- Simpan Galeri Foto (OPSIONAL) ---
        if ($request->hasFile('foto_galeri')) {
            foreach ($request->file('foto_galeri') as $foto) {
                if ($foto->isValid()) {
                    $namaGaleri = time() . '_galeri_' . $foto->getClientOriginalName();
                    $foto->move(public_path('images'), $namaGaleri);
                    ProdukFoto::create([
                        'produk_id' => $produk->id,
                        'nama_foto' => $namaGaleri,
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk "' . $produk->nama . '" berhasil ditambahkan!');
    }

    // =====================================================================
    // ADMIN: Form Edit Produk
    // =====================================================================
    public function edit($id)
    {
        $produk = Produk::with(['variasis', 'fotos'])->findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    }

    // =====================================================================
    // ADMIN: Update Produk
    // - foto_display   : OPSIONAL (kalau dikosongkan, foto lama dipakai)
    // - variasi[foto]  : OPSIONAL per variasi
    // =====================================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'kategori'          => 'required|string',
            'deskripsi'         => 'nullable|string',
            'foto_display'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variasi'           => 'required|array|min:1',
            'variasi.*.stok'    => 'required|integer|min:0',
            'variasi.*.harga'   => 'required|integer|min:0',
            'variasi.*.foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        // --- Update foto display kalau ada file baru ---
        $namaFotoDisplay = $produk->foto;
        if ($request->hasFile('foto_display')) {
            // Hapus foto lama
            $pathLama = public_path('images/' . $produk->foto);
            if (file_exists($pathLama)) {
                @unlink($pathLama);
            }
            $namaFotoDisplay = time() . '_display_' . $request->file('foto_display')->getClientOriginalName();
            $request->file('foto_display')->move(public_path('images'), $namaFotoDisplay);
        }

        // Hitung ulang harga & stok dari variasi baru
        $hargaTerendah = collect($request->variasi)->min('harga') ?? $produk->harga;
        $totalStok     = collect($request->variasi)->sum('stok');

        $produk->update([
            'nama'      => $request->nama,
            'kategori'  => strtoupper($request->kategori),
            'deskripsi' => $request->deskripsi,
            'foto'      => $namaFotoDisplay,
            'harga'     => $hargaTerendah,
            'stok'      => $totalStok,
        ]);

        // --- Kelola Variasi: Update lama, tambah baru, hapus yang dihilangkan ---
        $idVariasiDikirim = [];

        foreach ($request->variasi as $v) {
            $namaFotoVariasi = null;

            if (!empty($v['id'])) {
                // Variasi LAMA — update
                $variasi = ProdukVariasi::find($v['id']);
                if ($variasi) {
                    // Foto variasi: ganti kalau ada file baru, pakai lama kalau tidak
                    if (!empty($v['foto']) && $v['foto'] instanceof \Illuminate\Http\UploadedFile) {
                        // Hapus foto variasi lama
                        if ($variasi->foto) {
                            $pathFotoLama = public_path('images/' . $variasi->foto);
                            if (file_exists($pathFotoLama)) {
                                @unlink($pathFotoLama);
                            }
                        }
                        $namaFotoVariasi = time() . '_var_' . $v['foto']->getClientOriginalName();
                        $v['foto']->move(public_path('images'), $namaFotoVariasi);
                    } else {
                        $namaFotoVariasi = $variasi->foto; // Pakai foto lama
                    }

                    $variasi->update([
                        'ukuran' => $v['ukuran'] ?? null,
                        'warna'  => $v['warna'] ?? null,
                        'stok'   => $v['stok'],
                        'harga'  => $v['harga'],
                        'foto'   => $namaFotoVariasi,
                    ]);
                    $idVariasiDikirim[] = $variasi->id;
                }
            } else {
                // Variasi BARU — insert
                if (!empty($v['foto']) && $v['foto'] instanceof \Illuminate\Http\UploadedFile) {
                    $namaFotoVariasi = time() . '_var_' . $v['foto']->getClientOriginalName();
                    $v['foto']->move(public_path('images'), $namaFotoVariasi);
                }

                $variasiBaru = ProdukVariasi::create([
                    'produk_id' => $produk->id,
                    'ukuran'    => $v['ukuran'] ?? null,
                    'warna'     => $v['warna'] ?? null,
                    'stok'      => $v['stok'],
                    'harga'     => $v['harga'],
                    'foto'      => $namaFotoVariasi,
                ]);
                $idVariasiDikirim[] = $variasiBaru->id;
            }
        }

        // Hapus variasi yang sudah dihilangkan dari form
        $variasiDihapus = $produk->variasis()->whereNotIn('id', $idVariasiDikirim)->get();
        foreach ($variasiDihapus as $vd) {
            if ($vd->foto) {
                $path = public_path('images/' . $vd->foto);
                if (file_exists($path)) @unlink($path);
            }
            $vd->delete();
        }

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk "' . $produk->nama . '" berhasil diperbarui!');
    }

    // =====================================================================
    // ADMIN: Hapus Satu Produk
    // =====================================================================
    public function destroy($id)
    {
        $produk = Produk::with(['variasis', 'fotos'])->findOrFail($id);

        // Hapus foto display
        $pathDisplay = public_path('images/' . $produk->foto);
        if (file_exists($pathDisplay)) @unlink($pathDisplay);

        // Hapus foto tiap variasi
        foreach ($produk->variasis as $v) {
            if ($v->foto) {
                $path = public_path('images/' . $v->foto);
                if (file_exists($path)) @unlink($path);
            }
        }

        // Hapus foto galeri
        foreach ($produk->fotos as $f) {
            $path = public_path('images/' . $f->nama_foto);
            if (file_exists($path)) @unlink($path);
        }

        $produk->delete(); // cascade hapus variasi & galeri via DB

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk "' . $produk->nama . '" berhasil dihapus.');
    }

    // =====================================================================
    // ADMIN: Hapus Banyak Produk Sekaligus (Bulk Delete)
    // =====================================================================
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:produk,id',
        ]);

        $produkList = Produk::with(['variasis', 'fotos'])->whereIn('id', $request->ids)->get();
        $jumlah = $produkList->count();

        foreach ($produkList as $produk) {
            // Hapus foto display
            $pathDisplay = public_path('images/' . $produk->foto);
            if (file_exists($pathDisplay)) @unlink($pathDisplay);

            // Hapus foto tiap variasi
            foreach ($produk->variasis as $v) {
                if ($v->foto) {
                    $path = public_path('images/' . $v->foto);
                    if (file_exists($path)) @unlink($path);
                }
            }

            // Hapus foto galeri
            foreach ($produk->fotos as $f) {
                $path = public_path('images/' . $f->nama_foto);
                if (file_exists($path)) @unlink($path);
            }

            $produk->delete();
        }

        return redirect()->route('admin.produk.index')
            ->with('success', $jumlah . ' produk berhasil dihapus sekaligus.');
    }
}