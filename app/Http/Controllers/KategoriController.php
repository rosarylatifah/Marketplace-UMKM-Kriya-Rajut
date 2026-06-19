<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:100|unique:kategoris,nama',
    ], [
        'nama.required' => 'Nama kategori wajib diisi.',
        'nama.unique'   => 'Kategori dengan nama ini sudah ada.',
    ]);

    $data = Kategori::buatDariNama($request->nama);

    if (Kategori::where('slug', $data['slug'])->orWhere('kode', $data['kode'])->exists()) {
        return back()->withErrors(['nama' => 'Kategori dengan nama serupa sudah ada.'])->withInput();
    }

    Kategori::create($data);

    return redirect()->route('admin.produk.index')
        ->with('success', 'Kategori "' . $request->nama . '" berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $kategori = Kategori::findOrFail($id);

    $request->validate([
        'nama' => 'required|string|max:100|unique:kategoris,nama,' . $kategori->id,
    ], [
        'nama.required' => 'Nama kategori wajib diisi.',
        'nama.unique'   => 'Kategori dengan nama ini sudah ada.',
    ]);

    $kategori->update([
        'nama' => $request->nama,
        'slug' => Str::slug($request->nama),
        // 'kode' SENGAJA tidak diubah, biar produk yang sudah ada tetap nyambung
    ]);

    return redirect()->route('admin.produk.index')
        ->with('success', 'Kategori berhasil diubah jadi "' . $request->nama . '".');
}

public function destroy($id)
{
    $kategori = Kategori::findOrFail($id);
    $jumlahProduk = $kategori->jumlahProduk();

    $kategori->delete();

    $pesan = 'Kategori "' . $kategori->nama . '" berhasil dihapus.';
    if ($jumlahProduk > 0) {
        $pesan .= ' ' . $jumlahProduk . ' produk yang sebelumnya memakai kategori ini tetap aman, hanya saja tidak akan muncul di filter manapun sampai dipindah ke kategori lain.';
    }

    return redirect()->route('admin.produk.index')->with('success', $pesan);
}
}