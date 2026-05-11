<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        // Stat Cards
        $totalProduk      = Produk::count();
        $pesananBaru      = Pesanan::where('status', 'SEDANG DIPROSES')->count();
        $pesananAktif     = Pesanan::whereIn('status', ['SEDANG DIPROSES', 'DALAM PERJALANAN'])->count();
        $totalPendapatan  = Pesanan::where('status', 'SELESAI')->sum('total');

        // Aktivitas Terbaru — 10 pesanan terbaru
        $aktivitas = Pesanan::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'pesananBaru',
            'pesananAktif',
            'totalPendapatan',
            'aktivitas'
        ));
    }
}