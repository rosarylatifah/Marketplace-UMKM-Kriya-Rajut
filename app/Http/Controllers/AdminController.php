<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
// Pemanggilan objek Model Pesanan untuk berinteraksi dengan database
use App\Models\Pesanan; 

class AdminController extends Controller
{
    /**
     * SISI ADMIN: Menampilkan ringkasan atau metrik performa toko di Dashboard.
     * Alur PBO: Mengambil kumpulan (Collection) seluruh objek dari class Pesanan 
     * untuk dihitung jumlah datanya (State Measurement) dan diparsing ke halaman View.
     */
    public function dashboard()
    {
        // Ambil semua instansiasi objek pesanan dari database
        $pesanan = Pesanan::all();
        return view('admin.dashboard', compact('pesanan'));
    }

    /**
     * FR-07 (Sisi Admin): Menampilkan data pesanan masuk yang perlu diproses.
     * Alur PBO: Melakukan filtering state objek data Pesanan yang memiliki properti
     * status khusus (menyesuaikan dengan state yang digunakan pada database sistem).
     */
    public function pesananMasuk()
    {
        // Mengambil objek pesanan dengan filter status tertentu
        $pesanan = Pesanan::whereIn('status', ['SEDANG DIPROSES', 'PERLU DIKIRIM', 'DALAM PERJALANAN'])->get();
        return view('admin.pesanan_masuk', compact('pesanan'));
    }

    /**
     * SISI ADMIN: Menampilkan seluruh riwayat pesanan yang telah tuntas.
     * Alur PBO: Menarik data dengan restriksi nilai property status sama dengan 'SELESAI'.
     */
    public function pesananSelesai()
    {
        // Ambil data dari database yang statusnya 'SELESAI'
        $pesanan = Pesanan::where('status', 'SELESAI')->get();
        return view('admin.pesanan_selesai', compact('pesanan'));
    }

    /**
     * FR-08 (Sisi Admin): Menampilkan data transaksi yang dibatalkan oleh pelanggan.
     * Alur PBO: Menarik data objek dari database dengan kriteria attribute status state bernilai 'DIBATALKAN'.
     */
    public function pesananDibatalkan()
    {
        // Mengambil seluruh objek pesanan yang gagal atau dicancel pelanggan
        $pesanan = Pesanan::where('status', 'DIBATALKAN')->get();
        return view('admin.pesanan_batal', compact('pesanan'));
    }

    /**
     * SISI ADMIN: Menampilkan halaman utama manajemen atau kelola produk.
     */
    public function produk()
    {
        return view('admin.produk');
    }
}