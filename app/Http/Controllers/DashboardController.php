<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Produk Aktif (Sesuai stok barang yg ada di kelola produk)
        $totalProduk = Produk::sum('stok');

        // 2. Pesanan Baru yang Menunggu Konfirmasi Admin
        $pesananBaru = Pesanan::where('status', 'BELUM KONFIRMASI')->count();

        // 3. Pesanan Aktif yang Sedang Berjalan
        $pesananAktif = Pesanan::whereIn('status', ['SEDANG DIPROSES'])->count();

        // 4. TOTAL PENDAPATAN: Murni Produk (Total dikurangi Ongkir)
        $pendapatanKotorBulanIni = Pesanan::where('status', 'SELESAI')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum(\DB::raw('total - ongkir'));

        // 5. Aktivitas Terbaru — 4 pesanan terbaru
        $aktivitas = Pesanan::latest()->take(4)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'pesananBaru',
            'pesananAktif',
            'pendapatanKotorBulanIni',
            'aktivitas'
        ));
    }

    public function showPendapatanBulanan()
    {
        // Mengambil data pesanan selesai
        $pendapatan_bulanan = Pesanan::whereMonth('created_at', date('m'))
                                    ->whereYear('created_at', date('Y'))
                                    ->where('status', 'SELESAI')
                                    ->get();

        // Logika perhitungan: Total - Ongkir
        // Kita pake collection map buat ngitung subtotal per pesanan
        $total_keseluruhan = $pendapatan_bulanan->sum(function($p) {
            return $p->total - $p->ongkir; // Pastikan kolom di database namanya 'ongkir'
        });

        return view('admin.pendapatanperbulan', compact('pendapatan_bulanan', 'total_keseluruhan'));
    }

    public function exportData($format)
    {
        // Pastikan pakai 'with' kalau lo punya relasi ke User atau nama pembeli
        $data = \App\Models\Pesanan::with('user') // Contoh kalau ada relasi ke tabel User
                                    ->where('status', 'SELESAI')
                                    ->whereMonth('created_at', date('m'))
                                    ->whereYear('created_at', date('Y'))
                                    ->get();

        if ($format == 'excel') {
            // 1. Hitung total keseluruhan dari data yang ada
            $totalKeseluruhan = $data->sum(function($p) {
                return $p->total - $p->ongkir;
            });

            // 2. Ubah datanya jadi array, tambahin baris total di akhir
            $dataExcel = $data->map(function ($p) {
                return [
                    'ID Pesanan'    => $p->id_pesanan,
                    'Tanggal'       => $p->created_at->format('d/m/Y'),
                    'Nomor Telepon' => $p->no_hp,
                    'Nama Pembeli'  => $p->nama_pembeli,
                    'Barang'        => $p->nama_barang ?? '-', 
                    'Total (Murni)' => $p->total - $p->ongkir,
                ];
            })->toArray();

            // 3. Tambahin baris total sebagai baris terakhir
            $dataExcel[] = [
                'ID Pesanan'    => 'TOTAL KESELURUHAN',
                'Tanggal'       => '',
                'Nomor Telepon' => '',
                'Nama Pembeli'  => '',
                'Barang'        => '',
                'Total (Murni)' => $totalKeseluruhan,
            ];

            // 4. Export excel pake FastExcel
            return (new \Rap2hpoutre\FastExcel\FastExcel($dataExcel))->download('Laporan Pendapatan.xlsx');
        }

        if ($format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.export_pdf', compact('data'));
            return $pdf->download('Laporan Pendapatan.pdf');
        }
    }
}
