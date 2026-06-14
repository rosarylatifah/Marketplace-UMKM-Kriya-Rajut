@extends('layouts.pembeli')

@section('content')
<div class="py-12 max-w-6xl mx-auto px-4 lg:px-0">
    {{-- Header Halaman --}}
    <div class="mb-10">
        <span class="text-[10px] tracking-[0.5em] text-gray-400 mb-2 block">PESANANMU</span>
        <h1 class="text-2xl font-bold text-[#001f3f] tracking-[0.2em]">STATUS PESANAN</h1>
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    </div>

    {{-- Alert Success / Error Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-[11px] font-bold uppercase tracking-widest rounded-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-[11px] font-bold uppercase tracking-widest rounded-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Container Daftar Pesanan --}}
    <div class="space-y-4">
        @if(isset($pesanan) && $pesanan)
            {{-- Card Melebar --}}
            <div class="bg-white border border-gray-100 p-6 flex flex-col md:flex-row items-center gap-6 shadow-sm hover:shadow-md transition-all">
                
                {{-- Foto Produk Fallback Icon --}}
                <div class="w-24 h-24 bg-gray-50 border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                    @if(!empty($pesanan->foto))
                        <img src="{{ $pesanan->foto }}" class="w-full h-full object-cover" alt="{{ $pesanan->nama_barang }}">
                    @else
                        <i class="fa-solid fa-box text-2xl text-gray-300"></i>
                    @endif
                </div>

                {{-- Info Produk --}}
                <div class="flex-grow flex flex-col justify-between py-1 text-left">
                    <div>
                        <h3 class="font-bold text-sm text-[#001f3f] uppercase tracking-widest">
                            {{ $pesanan->nama_barang }}
                        </h3>
                        <p class="text-[10px] text-gray-400 mt-1 font-mono uppercase">ID: {{ $pesanan->id_pesanan }}</p>
                        
                        {{-- Tombol Lihat Detail --}}
                        <button 
                            class="btn-lihat-detail mt-2 text-[10px] tracking-widest font-bold text-[#001f3f] hover:underline block text-left"
                            data-id="{{ $pesanan->id_pesanan }}"
                            data-nama="{{ $pesanan->nama_barang }}"
                            data-total="{{ number_format($pesanan->total, 0, ',', '.') }}"
                            data-deskripsi="Pesanan kriya rajut resmi terdaftar di dalam sistem transaksi."
                            data-foto="{{ $pesanan->foto ?? '' }}">
                            Lihat Detail
                        </button>
                    </div>

                    {{-- Status Pesanan Dinamis --}}
                    <div class="mt-4 flex items-center gap-2">
                        @if($pesanan->status === 'SEDANG DIPROSES')
                            <div class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></div>
                            <p class="text-[10px] font-bold text-amber-500 italic tracking-widest uppercase">Sedang diproses</p>
                        @elseif($pesanan->status === 'DALAM PERJALANAN')
                            <div class="h-1.5 w-1.5 rounded-full bg-blue-400 animate-pulse"></div>
                            <p class="text-[10px] font-bold text-blue-500 italic tracking-widest uppercase">Dalam Perjalanan</p>
                        @elseif($pesanan->status === 'SELESAI')
                            <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                            <p class="text-[10px] font-bold text-emerald-500 italic tracking-widest uppercase">Pesanan Selesai</p>
                        @elseif($pesanan->status === 'DIBATALKAN')
                            <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                            <p class="text-[10px] font-bold text-red-500 italic tracking-widest uppercase">Pesanan Dibatalkan</p>
                        @else
                            <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                            <p class="text-[10px] font-bold text-blue-500 italic tracking-widest uppercase">{{ $pesanan->status }}</p>
                        @endif
                    </div>
                </div>

                {{-- Action Button (Kanan) --}}
                <div class="flex-shrink-0 w-full md:w-auto flex flex-col sm:flex-row md:flex-col gap-2">
                    <a href="https://wa.me/628123456789" target="_blank"
                        class="flex items-center justify-center gap-2 border border-gray-200 px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all w-full text-center">
                        <i class="fa-brands fa-whatsapp text-sm text-emerald-500"></i>
                        Kontak Penjual
                    </a>
                    {{-- Tombol Unduh Bukti (di bawah Kontak Penjual) --}}
                    
                    <button onclick="window.print()"
                        class="flex items-center justify-center gap-2 border border-[#001f3f] bg-[#001f3f] hover:bg-[#003366] text-white px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all w-full text-center cursor-pointer mt-2">
                        <i class="fa-solid fa-file-arrow-down text-xs"></i>
                        Unduh Bukti Pesanan
                    </button>

                    {{-- SEDANG DIPROSES --}}
                    @if($pesanan->status === 'SEDANG DIPROSES')

                    {{-- DALAM PERJALANAN: Tombol Konfirmasi Diterima --}}
                    @elseif($pesanan->status === 'DALAM PERJALANAN')
                        <form action="{{ route('pesanan.konfirmasi', $pesanan->id) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi pesanan sudah diterima? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest text-emerald-600 transition-all w-full text-center cursor-pointer">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                Konfirmasi Diterima
                            </button>
                        </form>

                    {{-- SELESAI: Pesanan sudah selesai --}}
                    @elseif($pesanan->status === 'SELESAI')
                        <div class="flex items-center justify-center gap-2 border border-emerald-200 bg-emerald-50 px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest text-emerald-600 w-full text-center">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            Pesanan Diterima
                        </div>
                    @endif

                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white border border-dashed border-gray-200 rounded-xl">
                <p class="text-gray-400 text-[10px] tracking-[0.3em]">Belum ada data pesanan untuk dilacak</p>
            </div>
        @endif
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-12 text-center">
        <a href="/katalog" class="bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold tracking-[0.2em] shadow-lg hover:bg-gray-800 transition-all inline-flex items-center gap-3">
            <i class="fa-solid fa-bag-shopping"></i>
            Mulai Belanja
        </a>
    </div>
</div>

{{-- FORM PEMBATALAN TERSEMBUNYI --}}
@if(isset($pesanan) && $pesanan)
<form id="form-batal-pesanan" action="{{ route('pembeli.pesanan.batalkan') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="id_pesanan" id="input-batal-id" value="">
</form>
@endif

{{-- MODAL DETAIL --}}
<div id="modal-detail" 
    class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col md:flex-row">
        <button onclick="tutupModalDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-black z-10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        {{-- Bagian Foto --}}
        <div class="w-full md:w-1/2 bg-gray-50 aspect-square flex items-center justify-center overflow-hidden">
            <img id="detail-foto" src="" class="w-full h-full object-cover" alt="Foto">
            <div id="detail-foto-placeholder" class="hidden text-gray-300 flex flex-col items-center gap-2">
                <i class="fa-solid fa-box text-4xl"></i>
                <span class="text-[9px] uppercase font-bold tracking-widest">No Image</span>
            </div>
        </div>

        {{-- Bagian Teks --}}
        <div class="p-8 md:w-1/2 flex flex-col justify-center text-left">
            <h2 id="detail-nama" class="text-lg font-bold text-[#001f3f] tracking-widest uppercase mb-1">Nama</h2>
            <p id="detail-harga" class="text-xs font-bold text-gray-400 mb-4 italic">IDR 0</p>
            <p id="detail-deskripsi" class="text-[11px] text-gray-500 leading-relaxed mb-6">Deskripsi...</p>
            
            {{-- Info Detail Pesanan --}}
            <div class="pt-4 border-t border-gray-100 flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Kode Pesanan</span>
                    <span id="detail-kode-text" class="text-[10px] font-mono font-bold text-[#001f3f]">ORD-xxx</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Total Bayar</span>
                    <span id="detail-total" class="text-[10px] font-bold text-[#001f3f]">Rp 0</span>
                </div>
            </div>
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
            @php
                // Tentukan warna berdasarkan status
                $statusColor = ($pesanan->status == 'SELESAI') ? '#059669' : '#d97706';
            @endphp

            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: {{ $statusColor }};">
                ✓ {{ $pesanan->status == 'BELUM KONFIRMASI' ? 'MENUNGGU KONFIRMASI ADMIN' : $pesanan->status }}
            </span>
        </div>

        <table style="width: 100%; font-size: 13px; border-collapse: collapse; margin-bottom: 24px;">
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Kode Pesanan</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $pesanan->id_pesanan ?? '-' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Tanggal</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;" id="tanggal-pesanan"></td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Nama Penerima</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $pesanan->nama_pembeli ?? '-' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Alamat</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $pesanan->alamat ?? '-' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Produk</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $pesanan->nama_barang ?? '-' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f3f5f1;">
                <td style="padding: 12px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Metode Pembayaran</td>
                <td style="padding: 12px 0; font-weight: 700; text-align: right;">{{ $pesanan->metode_pembayaran ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 16px 0; color: #9ca3af; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em;">Total Pembayaran</td>
                <td style="padding: 16px 0; font-weight: 800; font-size: 16px; text-align: right;">Rp {{ number_format($pesanan->total ?? 0, 0, ',', '.') }}</td>
            </tr>
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
    #pdf-content {
        display: block !important;
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        padding: 0 !important;
    }
    /* Tambahan biar jadi 1 halaman pas */
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* biar nggak ada sisa margin kertas */
    }
    body {
        margin: 0;
    }
}
</style>

<script>
    document.querySelectorAll('.btn-lihat-detail').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            
            document.getElementById('detail-nama').innerText = data.nama;
            document.getElementById('detail-harga').innerText = 'IDR ' + data.total;
            document.getElementById('detail-deskripsi').innerText = data.deskripsi;
            
            const imgEl = document.getElementById('detail-foto');
            const placeholderEl = document.getElementById('detail-foto-placeholder');
            
            if(data.foto) {
                imgEl.src = data.foto;
                imgEl.classList.remove('hidden');
                placeholderEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                placeholderEl.classList.remove('hidden');
            }
            
            document.getElementById('detail-kode-text').innerText = data.id;
            document.getElementById('detail-total').innerText = 'Rp ' + data.total;
            
            document.getElementById('modal-detail').classList.remove('hidden');
        });
    });

    function tutupModalDetail() {
        document.getElementById('modal-detail').classList.add('hidden');
    }

    function konfirmasiPembatalan(idPesanan) {
        if (confirm("Apakah kamu yakin ingin membatalkan pesanan " + idPesanan + "?\nTindakan ini akan mengembalikan stok produk.")) {
            document.getElementById('input-batal-id').value = idPesanan;
            document.getElementById('form-batal-pesanan').submit();
        }
    }
</script>
@endsection