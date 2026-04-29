@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-bold text-black tracking-tight">Pesanan Selesai</h1>
    <p class="text-gray-600 font-medium">Riwayat pemesanan yang telah selesai</p>
</div>

<div class="mb-4">
    <h2 class="text-lg font-bold text-black uppercase tracking-wider">Daftar Pesanan ({{ count($pesanan_selesai) }})</h2>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left border border-gray-300">
        <thead class="bg-pink-600">
            <tr class="text-white uppercase text-xs font-black tracking-widest">
                <th class="px-4 py-4">ID Pesanan</th>
                <th class="px-4 py-4">Nama Pembeli</th>
                <th class="px-4 py-4 text-center">Nama Barang</th>
                <th class="px-4 py-4 text-center">Jumlah Item</th>
                <th class="px-4 py-4">Total</th>
                <th class="px-4 py-4 text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white text-black font-medium">
            @forelse ($pesanan_selesai as $p)
            <tr class="hover:bg-pink-50 transition-colors">
                <td class="px-4 py-4 font-bold uppercase">{{ $p->id_pesanan }}</td>
                <td class="px-4 py-4">{{ $p->nama_pembeli }}</td>
                <td class="px-4 py-4 text-center">{{ $p->nama_barang }}</td>
                <td class="px-4 py-4 text-center">
                    {{-- Kalau di database belum ada kolom jumlah, sementara isi 1 aja --}}
                    {{ $p->jumlah ?? '1' }}
                </td>
                <td class="px-4 py-4 font-bold">
                    Rp{{ number_format($p->total, 0, ',', '.') }}
                </td>
                <td class="px-4 py-4 text-center">
                    {{ $p->created_at->format('d/m/Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                    Belum ada riwayat pesanan selesai nih... 🧶
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection