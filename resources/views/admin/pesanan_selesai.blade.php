@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Riwayat</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pesanan Selesai</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Riwayat pemesanan yang telah selesai.</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    {{-- Table Header --}}
    <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Daftar Pesanan ({{ count($pesanan_selesai) }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4 text-center">Nama Barang</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4 text-center">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_selesai as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    <td class="px-8 py-4 text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</td>
                    <td class="px-8 py-4 text-sm text-gray-700">{{ $p->nama_pembeli }}</td>
                    <td class="px-8 py-4 text-sm text-gray-700 text-center">{{ $p->nama_barang }}</td>
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f]">
                        Rp{{ number_format($p->total, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-4 text-center">
                        <span class="text-[11px] uppercase tracking-widest text-gray-400">{{ $p->created_at->format('d/m/Y') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-circle-check text-3xl text-gray-200"></i>
                            <p class="text-sm text-gray-400 uppercase tracking-widest">Belum ada riwayat pesanan selesai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection