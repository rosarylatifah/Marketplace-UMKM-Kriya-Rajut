<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
public function store(Request $request)
{
    // Cek duplikat manual pakai session flash, bukan withErrors
    if (Kategori::whereRaw('LOWER(nama) = ?', [strtolower($request->nama)])->exists()) {
        return redirect()->route('admin.produk.index')
            ->with('error', 'Kategori dengan nama "' . $request->nama . '" sudah ada.');
    }

    $request->validate([
        'nama' => 'required|string|max:100',
    ]);

    $data = Kategori::buatDariNama($request->nama);

    if (Kategori::where('slug', $data['slug'])->orWhere('kode', $data['kode'])->exists()) {
        return redirect()->route('admin.produk.index')
            ->with('error', 'Kategori dengan nama serupa sudah ada.');
    }

    Kategori::create($data);

    return redirect()->route('admin.produk.index')
        ->with('success', 'Kategori "' . $request->nama . '" berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $kategori = Kategori::findOrFail($id);

    // Cek duplikat manual (exclude diri sendiri)
    if (Kategori::whereRaw('LOWER(nama) = ?', [strtolower($request->nama)])
        ->where('id', '!=', $kategori->id)
        ->exists()) {
        return redirect()->route('admin.produk.index')
            ->with('error', 'Kategori dengan nama "' . $request->nama . '" sudah ada.');
    }

    $request->validate([
        'nama' => 'required|string|max:100',
    ]);

    $kategori->update([
        'nama' => $request->nama,
        'slug' => \Illuminate\Support\Str::slug($request->nama),
    ]);

    return redirect()->route('admin.produk.index')
        ->with('success', 'Kategori berhasil diubah jadi "' . $request->nama . '".');
}

public function destroy($id)
{
    $kategori = Kategori::findOrFail($id);
    $jumlahProduk = $kategori->jumlahProduk();

    // ❌ Cegah hapus kalau masih ada produk
    if ($jumlahProduk > 0) {
        return redirect()->route('admin.produk.index')
            ->with('error', 'Kategori "' . $kategori->nama . '" tidak bisa dihapus karena masih memiliki ' . $jumlahProduk . ' produk. Pindahkan produk ke kategori lain terlebih dahulu.');
    }

    $kategori->delete();

    return redirect()->route('admin.produk.index')
        ->with('success', 'Kategori "' . $kategori->nama . '" berhasil dihapus.');
}
}