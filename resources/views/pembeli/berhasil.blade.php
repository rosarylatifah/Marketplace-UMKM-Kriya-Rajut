@extends('layouts.pembeli')

@section('content')
@php $info = session('pesanan_info', []); @endphp

<div class="py-10 flex justify-center items-start px-4">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-8 sm:p-12 text-center">

        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-[#F3F5F1] rounded-full flex items-center justify-center">
                <i class="fa-solid fa-check text-[#001f3f] text-xl"></i>
            </div>
        </div>

        <h1 class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-2">Transaksi</h1>
        <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pembayaran Berhasil</p>
        <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        <p class="text-sm text-gray-500 mt-4 leading-relaxed">Terima kasih telah berbelanja di Kriya Rajut.<br>Pesananmu sedang kami proses dengan sepenuh hati.</p>

        <div class="mt-6 bg-[#001f3f] rounded-lg px-6 py-4">
            <p class="text-[11px] uppercase tracking-[0.2em] text-gray-300 mb-1">Kode Pesananmu</p>
            <p class="text-lg font-bold text-white tracking-widest">{{ $info['id_pesanan'] ?? '-' }}</p>
            <p class="text-[10px] text-gray-400 mt-1">Simpan kode ini untuk melacak pesananmu</p>
        </div>

        <div class="bg-[#F3F5F1] rounded-lg px-6 py-5 text-left mt-4 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-xs uppercase tracking-[0.2em] text-gray-500">Status Pesanan</span>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-600">Dikonfirmasi</span>
                </div>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between items-center">
                <span class="text-xs uppercase tracking-[0.2em] text-gray-500">Estimasi Pengiriman</span>
                <span class="text-xs font-bold uppercase tracking-widest text-[#001f3f]">3 – 5 Hari Kerja</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 mt-8">
            <button onclick="downloadPDF()"
                class="flex items-center justify-center gap-2 w-full text-center bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-xs py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fa-solid fa-file-arrow-down"></i> Unduh Bukti Pesanan
            </button>
            <a href="/lacak-pesanan"
                class="block w-full text-center bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.2em] text-xs py-4 rounded-full transition-all duration-200">
                <i class="fa-solid fa-location-dot mr-2"></i> Lacak Pesanan
            </a>
            <a href="/"
                class="block w-full text-center bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.2em] text-xs py-4 rounded-full transition-all duration-200">
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>

{{-- Template PDF --}}
<div id="pdf-content" class="hidden">
    <div style="font-family: 'Plus Jakarta Sans', sans-serif; max-width: 600px; margin: 0 auto; padding: 48px; color: #001f3f;">
        <div style="text-align: center; margin-bottom: 40px; border-bottom: 1px solid #e5e7eb; padding-bottom: 32px;">
            <p style="font-size: 20px; font-weight: 800; letter-spacing: 0.3em; text-transform: uppercase; margin: 0;">
                KRIYA<span style="color: #9ca3af; font-weight: 300;">RAJUT</span>
            </p>
            <p style="font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase; color: #9ca3af; margin-top: 6px;">Bukti Pesanan</p>
        </div>
        <div style="background: #f3f5f1; border-radius: 8px; padding: 16px 24px; display: flex; justify-content: space-between; margin-bottom: 24px;">
            <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: #9ca3af;">Status</span>
            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #059669;">✓ Dikonfirmasi</span>
        </div>
        <table style="width: 100%; font-size: 13px; border-collapse: collapse; margin-bottom: 24px;">
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Kode Pesanan</td><td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $info['id_pesanan'] ?? '-' }}</td></tr>
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Tanggal</td><td style="padding: 12px 0; font-weight: 700; text-align: right;" id="tanggal-pesanan"></td></tr>
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Nama Penerima</td><td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $info['nama_pembeli'] ?? '-' }}</td></tr>
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Alamat</td><td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $info['alamat'] ?? '-' }}</td></tr>
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Produk</td><td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $info['nama_barang'] ?? '-' }}</td></tr>
            <tr style="border-bottom: 1px solid #f3f5f1;"><td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Metode Pembayaran</td><td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $info['metode'] ?? '-' }}</td></tr>
            <tr><td style="padding: 16px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Total Pembayaran</td><td style="padding: 16px 0; font-weight: 800; font-size: 16px; text-align: right;">Rp {{ number_format($info['total'] ?? 0, 0, ',', '.') }}</td></tr>
        </table>
        <div style="background: #f3f5f1; border-radius: 8px; padding: 16px 24px; display: flex; justify-content: space-between; margin-bottom: 40px;">
            <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: #9ca3af;">Estimasi Pengiriman</span>
            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em;">3 – 5 Hari Kerja</span>
        </div>
        <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 24px;">
            <p style="font-size: 10px; color: #9ca3af; letter-spacing: 0.15em; text-transform: uppercase;">© 2026 Kriya Rajut Studio. Terima kasih telah berbelanja.</p>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #pdf-content, #pdf-content * { visibility: visible; }
    #pdf-content { display: block !important; position: fixed; top: 0; left: 0; width: 100%; }
}
</style>

<script>
    document.getElementById('tanggal-pesanan').textContent = new Date().toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
    function downloadPDF() { window.print(); }
    document.addEventListener('DOMContentLoaded', function () {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () { window.history.pushState(null, "", window.location.href); };
    });
</script>
@endsection
