@extends('layouts.pembeli')

@section('content')

@php
    $info = session('pesanan_info', []);
@endphp

<div class="py-12 flex justify-center items-center">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-12 text-center">

        {{-- Ikon Sukses --}}
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-[#F3F5F1] rounded-full flex items-center justify-center">
                <i class="fa-solid fa-check text-[#001f3f] text-xl"></i>
            </div>
        </div>

        {{-- Header --}}
        <h1 class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mb-2">Transaksi</h1>
        <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pembayaran Berhasil</p>
        <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        <p class="text-sm text-gray-400 mt-4 leading-relaxed">Terima kasih telah berbelanja di Kriya Rajut.<br>Pesananmu sedang kami proses dengan sepenuh hati.</p>

        {{-- Kode Pesanan --}}
        <div class="mt-6 bg-[#001f3f] rounded-lg px-6 py-4">
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-1">Kode Pesananmu</p>
            <p class="text-lg font-bold text-white tracking-widest">{{ $info['id_pesanan'] ?? '-' }}</p>
            <p class="text-[10px] text-gray-400 mt-1">Simpan kode ini untuk melacak pesananmu</p>
        </div>

        {{-- Info Box --}}
        <div class="bg-[#F3F5F1] rounded-lg px-6 py-5 text-left mt-4 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Status Pesanan</span>
                <div class="flex items-center gap-2">
                    {{-- Warna diubah ke amber biar terkesan 'pending' bukan 'selesai' --}}
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-amber-600">MENUNGGU KONFIRMASI ADMIN</span>
                </div>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between items-center">
                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Estimasi Pengiriman</span>
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#001f3f]">3 – 5 Hari Kerja</span>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col gap-3 mt-8">
            <a href="/lacak-pesanan"
                class="block w-full text-center bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.25em] text-[11px] py-4 rounded-full transition-all duration-200">
                <i class="fa-solid fa-location-dot mr-2"></i> Lacak Pesanan
            </a>
            <a href="/"
                class="block w-full text-center bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.25em] text-[11px] py-4 rounded-full transition-all duration-200">
                Kembali ke Halaman Utama
            </a>
        </div>

    </div>
</div>

<script>
    // Mengisi tanggal otomatis pada invoice
    document.getElementById('tanggal-pesanan').textContent = new Date().toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });

    // Memicu cetak invoice bawaan browser
    function downloadPDF() {
        window.print();
    }

    // 🔥 FIX BUG NAVIGASI BACK BROWSER
    // Mengunci history browser agar saat di-back tidak kembali ke halaman pembayaran
    document.addEventListener('DOMContentLoaded', function () {
        // Daftarkan state baru di history browser
        window.history.pushState(null, "", window.location.href);
        
        // Ketika tombol back browser diklik oleh pembeli
        window.onpopstate = function () {
            // Paksa history maju kembali ke halaman berhasil
            window.history.pushState(null, "", window.location.href);
            
            // OPSIONAL: Kamu bisa membiarkannya diam di halaman berhasil, 
            // atau jika ingin dialihkan langsung ke halaman utama, aktifkan baris di bawah ini:
            // window.location.href = "/"; 
        };
    });
</script>
@endsection