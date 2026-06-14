<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Produk Aktif
        $totalProduk = Produk::count();

        // 2. FIX LOGIKA: Pesanan Baru yang Menunggu Konfirmasi Admin 
        // (Ganti 'Perlu Dikirim' sesuai teks status awal pesanan di database lu ya, Zar!)
        $pesananBaru = Pesanan::where('status', 'BELUM KONFIRMASI')->count();

        // 3. FIX HURUF: Pesanan Aktif yang Sedang Berjalan 
        // (Pastiin huruf besar/kecilnya sama persis dengan yang ada di database lu)
        $pesananAktif = Pesanan::whereIn('status', ['SEDANG DIPROSES'])->count();

        // 4. FIX TOTAL PENDAPATAN: Murni Produk (Total dikurangi Ongkir)
        $pendapatanKotorBulanIni = Pesanan::where('status', 'SELESAI')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum(\DB::raw('total - ongkir'));

        // Aktivitas Terbaru — 10 pesanan terbaru
        $aktivitas = Pesanan::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'pesananBaru',
            'pesananAktif',
            'pendapatanKotorBulanIni',
            'aktivitas'
        ));
    }
}