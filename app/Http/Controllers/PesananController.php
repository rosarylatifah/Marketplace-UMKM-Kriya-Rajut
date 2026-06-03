<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;

class PesananController extends Controller
{   
    /**
     * SISI ADMIN: Melihat history dari seluruh transaksi tanpa filter status.
     */
    public function lihatSemua()
    {
        $semua_pesanan = Pesanan::orderBy('created_at', 'desc')->get();
        return view('admin.lihat_semua', compact('semua_pesanan'));
    }

    /**
     * SISI ADMIN: Melihat data pesanan aktif.
     */
    public function index()
    {
        $pesanan_masuk = Pesanan::whereIn('status', ['SEDANG DIPROSES', 'DALAM PERJALANAN'])
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('admin.pesanan_masuk', compact('pesanan_masuk'));
    }

    /**
     * SISI ADMIN: Melihat data pesanan yang telah sukses diselesaikan.
     */
    public function selesai()
    {
        $pesanan_selesai = Pesanan::where('status', 'SELESAI')
                                  ->orderBy('updated_at', 'desc')
                                  ->get();
        return view('admin.pesanan_selesai', compact('pesanan_selesai'));
    }

    /**
     * FR-08 (Sisi Admin): Admin dapat melihat data pesanan yang dibatalkan oleh pelanggan.
     * Mengambil data nomor telepon user untuk keperluan alur pengembalian dana manual.
     */
    public function dibatalkan()
    {
        $pesanan_batal = Pesanan::where('status', 'DIBATALKAN')
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Melakukan mapping data untuk menyisipkan nomor handphone pembeli dari tabel users
        $pesanan_batal->transform(function ($pesanan) {
            $user = User::where('email', $pesanan->email)->first();
            // Menyesuaikan jika di tabel user menggunakan nama kolom no_hp atau telepon
            $pesanan->no_hp = $user ? ($user->no_hp ?? $user->telepon ?? $user->no_telp) : null; 
            return $pesanan;
        });

        return view('admin.pesanan_batal', compact('pesanan_batal'));
    }
    
    /**
     * Menampilkan formulir modifikasi item pesanan secara manual oleh Admin.
     */
    public function tambahItem($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
        $produk = Produk::all();
        return view('admin.tambah_item_pesanan', compact('pesanan', 'produk'));
    }

    /**
     * Memproses pembaruan data item dan total nominal belanja di dalam sebuah pesanan.
     */
    public function updatePesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $totalBaru = $pesanan->total + ($request->harga_tambahan ?? 0) + ($request->ongkir ?? 0);

        $pesanan->update([
            'nama_barang' => $pesanan->nama_barang . ', ' . $request->barang_baru,
            'total' => $totalBaru,
        ]);

        return redirect('/admin/pesanan-masuk')->with('success', 'Pesanan berhasil diperbarui!');
    }

    /**
     * Menghapus secara permanen data riwayat transaksi transaksi dari sistem.
     */
    public function destroy($id)
    {
        Pesanan::destroy($id);
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }

    /**
     * FR-07 (Sisi Admin): Mengubah status pesanan produk.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:SEDANG DIPROSES,DALAM PERJALANAN,SELESAI,DIBATALKAN'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * SISI PEMBELI: Menampilkan halaman status/lacak pesanan berdasarkan email dan kode order.
     * Menggunakan ->first() untuk mencegah error "Property does not exist on this collection instance".
     */
    public function statusPesanan(Request $request)
    {
        $email = $request->query('email');
        $kode = $request->query('kode');

        // Diubah menjadi first() agar mengembalikan single object Pesanan, bukan kumpulan collection
        $pesanan = Pesanan::where('email', $email)
                          ->where('id_pesanan', $kode)
                          ->first();

        return view('pembeli.status', compact('pesanan'));
    }
}