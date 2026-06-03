@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pesanan Dibatalkan</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Daftar pesanan kriya rajut yang telah dibatalkan oleh pembeli atau sistem.</p>
</div>

{{-- Alert Success Notifikasi --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    {{-- Table Header --}}
    <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Daftar Pembatalan ({{ count($pesanan_batal) }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4">Detail Barang</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_batal as $index => $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    
                    {{-- 1. ID Pesanan --}}
                    <td class="px-8 py-4">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</span>
                            <span class="text-[9px] text-gray-400 mt-1">
                                {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') : 'Waktu tdk tersedia' }} WIB
                            </span>
                        </div>
                    </td>

                    {{-- 2. Nama Pembeli --}}
                    <td class="px-8 py-4 text-sm font-semibold text-gray-700">
                        {{ $p->nama_pembeli }}
                    </td>

                    {{-- 3. Detail Barang (Berdasarkan String Database Anda) --}}
                    <td class="px-8 py-4">
                        <button data-modal-target="modal-batal-{{ $index }}" data-modal-toggle="modal-batal-{{ $index }}" type="button" class="text-left block group">
                            <div class="flex items-center gap-3 bg-gray-50/50 p-2 rounded-lg border border-gray-100 group-hover:border-[#001f3f] group-hover:bg-white transition-all duration-150">
                                <div class="w-8 h-8 bg-[#001f3f] text-white rounded-md flex-shrink-0 flex items-center justify-center text-[10px] font-bold uppercase">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wide line-clamp-1 max-w-[250px]">
                                        {{ $p->nama_barang }}
                                    </span>
                                    <span class="text-[9px] text-blue-600 font-semibold underline mt-0.5">Klik untuk Detail & Refund</span>
                                </div>
                            </div>
                        </button>

                        {{-- MODAL POPUP FIX ACCORDING TO DATABASE --}}
                        <div id="modal-batal-{{ $index }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
                            <div class="relative w-full max-w-lg h-auto">
                                <div class="relative bg-white border border-gray-300 p-6 shadow-2xl text-left rounded-xl">
                                    
                                    <div class="flex flex-col gap-4">
                                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                            <div>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest block">Rincian Pembatalan</span>
                                                <h3 class="text-sm font-bold text-[#001f3f] font-mono mt-0.5">{{ $p->id_pesanan }}</h3>
                                            </div>
                                            <button data-modal-hide="modal-batal-{{ $index }}" type="button" class="text-gray-400 hover:text-red-500">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="space-y-3 text-xs">
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Produk yang Dibatalkan:</span>
                                                <p class="text-gray-800 font-bold uppercase bg-gray-50 p-2.5 rounded border border-gray-100 mt-1 leading-relaxed">
                                                    {{ $p->class ?? $p->nama_barang }}
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Nama Pemesan</span>
                                                    <span class="text-sm font-semibold text-gray-800">{{ $p->nama_pembeli }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Email</span>
                                                    <span class="text-sm font-mono text-gray-600">{{ $p->email }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 pt-1">
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Total Dana Terbayar</span>
                                                    <span class="text-sm font-bold text-[#001f3f]">Rp{{ number_format($p->total, 0, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Biaya Ongkir</span>
                                                    <span class="text-sm font-medium text-gray-600">Rp{{ number_format($p->ongkir ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tombol Hubungi WhatsApp Pembeli --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            @php
                                                // Jika di database no_hp pembeli kosong, gunakan nomor default atau tampilkan input / link WA langsung
                                                $nomorTujuan = $p->no_hp ?? '628123456789'; // Ganti dengan nomor default admin jika data pembeli kosong
                                                $pesanWA = "Halo " . $p->nama_pembeli . ", kami dari Admin Kriya Rajut ingin mengonfirmasi pengembalian dana untuk ID Pesanan " . $p->id_pesanan . " sebesar Rp " . number_format($p->total, 0, ',', '.') . " yang dibatalkan. Mohon kirimkan rekening Anda. Terima kasih.";
                                                $linkWA = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $nomorTujuan) . "&text=" . urlencode($pesanWA);
                                            @endphp
                                            
                                            <a href="{{ $linkWA }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider py-3 rounded-lg transition-all shadow-md">
                                                <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Pembeli via WhatsApp
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- 4. Total Bayar --}}
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f]">
                        Rp{{ number_format($p->total, 0, ',', '.') }}
                    </td>

                    {{-- 5. Status --}}
                    <td class="px-8 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest bg-red-50 text-red-600 border border-red-200 rounded-lg px-3 py-1.5 inline-block">
                            {{ $p->status }}
                        </span>
                    </td>

                    {{-- 6. Aksi Hapus --}}
                    <td class="px-8 py-4 text-center">
                        <form action="{{ route('pesanan.hapus', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus log riwayat pembatalan ini?')"
                                class="text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 border border-red-100 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all">
                                Hapus Log
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <p class="text-sm text-gray-400 uppercase tracking-widest">Tidak ada data pesanan yang dibatalkan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection