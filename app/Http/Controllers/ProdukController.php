<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\FotoProduk;
use Illuminate\Http\Request;

class ProdukController extends Controller 
{
    public function index($category = null)
{
    // Mulai query dengan eager loading relasi 'fotos'
    $query = Produk::with('fotos');

    // Jika ada kategori yang dipilih, lakukan filter (sesuaikan logika filter kategori kamu jika berbeda)
    if ($category && $category !== 'semua') {
        // Mengganti tanda strip kembali ke spasi/format asli jika diperlukan
        $categoryName = str_replace('-', ' ', $category);
        $query->where('kategori', $categoryName);
    }

    $produk = Produk::with('fotos')->get();
    $currentCategory = $category ? ucwords(str_replace('-', ' ', $category)) : 'Semua';

    return view('admin.kelola_produk', compact('produk', 'currentCategory'));
}

public function katalog($category = null)
{
    $query = Produk::with('fotos');

    if ($category && $category !== 'semua') {
        $categoryName = str_replace('-', ' ', $category);
        $query->where('kategori', $categoryName);
    }

    $produk = $query->get();
    $currentCategory = $category ? ucwords(str_replace('-', ' ', $category)) : 'Semua';

    // Wajib pakai pembeli.katalog karena filenya ada di dalam folder 'pembeli'
    return view('pembeli.katalog', compact('produk', 'currentCategory'));
}

    public function create()
    {
        return view('admin.create'); 
    }

    public function store(Request $request)
{
    // 1. Validasi input form admin
    $request->validate([
        'nama' => 'required',
        'kategori' => 'required',
        'stok' => 'required|numeric',
        'harga' => 'required|numeric',
        'deskripsi' => 'nullable',
        'foto' => 'required|array', 
        'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // Tempat menampung nama file yang sukses di-upload
    $uploaded_images = [];

    // 2. Upload SEMUA foto terlebih dahulu ke folder public/images
    if ($request->hasFile('foto')) {
        foreach ($request->file('foto') as $index => $file_foto) {
            // Buat nama file unik memakai timestamp dan index array
            $nama_file = time() . '_' . $index . '_' . str_replace(' ', '_', $file_foto->getClientOriginalName());
            
            // Pindahkan file fisik ke folder public/images
            $file_foto->move(public_path('images'), $nama_file);
            
            // Catat namanya ke dalam array pembantu
            $uploaded_images[] = $nama_file;
        }
    }

    // 3. Ambil nama file pertama dari array untuk dijadikan foto utama produk
    $foto_utama = !empty($uploaded_images) ? $uploaded_images[0] : null;

    // 4. Simpan data produk ke tabel 'produk'
    $produk = Produk::create([
        'nama' => $request->nama,
        'kategori' => $request->kategori,
        'stok' => $request->stok,
        'harga' => $request->harga,
        'deskripsi' => $request->deskripsi,
        'foto' => $foto_utama, // Berupa string nama file utama
    ]);

    // 5. Simpan semua daftar foto yang ada di array ke tabel relasi 'foto_produk'
    foreach ($uploaded_images as $nama_img) {
        \App\Models\FotoProduk::create([
            'produk_id' => $produk->id,
            'nama_foto' => $nama_img,
        ]);
    }

    return redirect()->route('admin.produk.index')->with('success', 'Produk dan foto tambahan berhasil disimpan!');
}

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
                unlink(public_path('images/' . $produk->foto));
            }
            $nama_foto = time().'.'.$request->foto->extension();  
            $request->foto->move(public_path('images'), $nama_foto);
            $produk->foto = $nama_foto;
        }

        $produk->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'foto' => $produk->foto,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
            unlink(public_path('images/' . $produk->foto));
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function home()
    {
        $produk = Produk::with('fotos')->get(); 
        return view('pembeli.home', compact('produk')); 
    }
}