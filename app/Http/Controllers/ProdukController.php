<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller 
{
public function index($category = null)
{
    // Mapping dari slug URL ke Nama Kategori yang bagus
    $categories = [
        'semua' => 'Semua',
        'pakaian' => 'Pakaian',
        'aksesoris' => 'Aksesoris',
        'dekorasi' => 'Dekorasi',
        'amigurumi' => 'Amigurumi',
        'tas-wadah' => 'Tas & Wadah'
    ];

    // Ambil nama kategori asli berdasarkan slug, kalau tidak ada default ke 'Semua'
    $currentCategory = $categories[$category] ?? 'Semua';

    return view('pembeli.katalog', compact('currentCategory'));
}

    public function create()
    {
        return view('admin.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nama_foto = time().'.'.$request->foto->extension();  
        $request->foto->move(public_path('images'), $nama_foto);

        Produk::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'foto' => $nama_foto,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambah!');
    }

    public function edit($id)
    {
        // Cari data produk yang mau diedit
        $produk = Produk::findOrFail($id);
        // Lempar ke file produk_edit.blade.php
        return view('admin.produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Validasi data (foto nggak 'required' karena belum tentu user mau ganti foto)
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Logic kalau user upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama biar hemat storage
            if ($produk->foto && file_exists(public_path('images/' . $produk->foto))) {
                unlink(public_path('images/' . $produk->foto));
            }
            // Upload foto baru
            $nama_foto = time().'.'.$request->foto->extension();  
            $request->foto->move(public_path('images'), $nama_foto);
            $produk->foto = $nama_foto;
        }

        // Update data lainnya
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
}