<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;

class PesananController extends Controller
{   
    public function lihatSemua()
    {
        $semua_pesanan = \App\Models\Pesanan::orderBy('created_at', 'desc')->get();
        return view('admin.lihat_semua', compact('semua_pesanan'));
    }

    public function index()
    {
        // Ambil data (Nama variabel kiri bebas, tapi yang di dalam compact HARUS sesuai Blade)
        $pesanan_masuk = Pesanan::whereIn('status', ['SEDANG DIPROSES', 'DALAM PERJALANAN'])->get();
        
        return view('admin.pesanan_masuk', compact('pesanan_masuk'));
    }

    public function selesai()
    {
        // Ambil data
        $pesanan_selesai = Pesanan::where('status', 'SELESAI')->get();
        
        return view('admin.pesanan_selesai', compact('pesanan_selesai'));
    }

    // Form Tambah Item
    public function tambahItem($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
        $produk = Produk::all();
        return view('admin.tambah_item_pesanan', compact('pesanan', 'produk'));
    }

    // Proses Simpan Update
    public function updatePesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Hitung Total Baru
        $totalBaru = $pesanan->total + ($request->harga_tambahan ?? 0) + ($request->ongkir ?? 0);

        $pesanan->update([
            'nama_barang' => $pesanan->nama_barang . ', ' . $request->barang_baru,
            'total' => $totalBaru,
        ]);

        return redirect('/admin/pesanan-masuk')->with('success', 'Pesanan diupdate!');
    }

    // Fitur Hapus
    public function destroy($id)
    {
        Pesanan::destroy($id);
        return redirect()->back()->with('success', 'Pesanan dihapus!');
    }

    // Tombol Update Status Pesanan-Masuk
    public function update(Request $request, $id)
    {
    $pesanan = \App\Models\Pesanan::findOrFail($id);
    $pesanan->status = $request->status;
    $pesanan->save();

    return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}