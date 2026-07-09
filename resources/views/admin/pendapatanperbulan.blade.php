@extends('layouts.admin')

@section('content')

{{-- Header yang konsisten dengan Dashboard --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Laporan Keuangan</p>
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6">
        <div>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pendapatan Bulan Ini</h1>
            <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
            <p class="text-sm text-gray-400 mt-3">Rincian pendapatan kotor dari pesanan yang selesai bulan ini.</p>
        </div>
        
        <div class="flex flex-col items-end gap-3">
            <a href="/admin/dashboard" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-colors flex items-center gap-1">
                ← Kembali ke Dashboard
            </a>
            
            {{-- FORM FILTER --}}
            <form action="{{ route('admin.pendapatan.bulanan') }}" method="GET" class="flex gap-2 mb-4">
                <select name="bulan" class="text-[10px] p-2 border rounded-lg uppercase">
                    @for ($m=1; $m<=12; $m++)
                        <option value="{{ sprintf("%02d", $m) }}" {{ isset($bulan) && $bulan == sprintf("%02d", $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m, 1)) }}
                        </option>
                    @endfor
                </select>
                
                <select name="tahun" class="text-[10px] p-2 border rounded-lg">
                    @for ($y=date('Y'); $y>=2025; $y--)
                        <option value="{{ $y }}" {{ isset($tahun) && $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                
                <button type="submit" class="bg-[#001f3f] text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase">Filter</button>
            </form>

            {{-- TOMBOL EXPORT (DITAMBAHIN QUERY PARAMETER) --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.export.pendapatan', ['format' => 'excel', 'bulan' => $bulan ?? date('m'), 'tahun' => $tahun ?? date('Y')]) }}" 
                class="bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-lg hover:bg-emerald-700 transition-all">
                    Export Excel
                </a>
                <a href="{{ route('admin.export.pendapatan', ['format' => 'pdf', 'bulan' => $bulan ?? date('m'), 'tahun' => $tahun ?? date('Y')]) }}" 
                class="bg-[#001f3f] text-white text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-lg hover:bg-[#002d5a] transition-all">
                    Export PDF
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Tabel Pendapatan Bulanan --}}
<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                <th class="pb-4 font-bold">ID Pesanan</th>
                <th class="pb-4 font-bold">Tanggal</th>
                <th class="pb-4 font-bold">Nomor Telepon</th>
                <th class="pb-4 font-bold">Nama Pembeli</th>
                <th class="pb-4 font-bold">Detail Barang</th>
                <th class="pb-4 font-bold text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 text-xs">
            @forelse($pendapatan_bulanan as $p)
            <tr class="hover:bg-[#F3F5F1]/50 transition-colors duration-150">
                <td class="py-5 font-bold text-[#001f3f]">#{{ $p->id_pesanan }}</td>
                <td class="py-5 text-gray-400">{{ $p->created_at->format('d M Y') }}</td>
                <td class="py-5 text-gray-700">{{ $p->no_hp }}</td>
                <td class="py-5 text-gray-700">{{ $p->nama_pembeli }}</td>
                <td class="py-5">
                    <div class="flex flex-col gap-1">
                        @foreach(explode(',', $p->nama_barang) as $item)
                            <div class="text-gray-700"><span class="font-medium text-xs">{{ trim($item) }}</span></div>
                        @endforeach
                    </div>
                </td>
                <td class="py-5 font-semibold text-gray-800 text-right">
                    Rp {{ number_format($p->total - $p->ongkir, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-gray-400 italic">Belum ada pendapatan bulan ini.</td>
            </tr>
            @endforelse
            
            {{-- Baris Total yang udah diperbaiki colspan-nya --}}
            <tr class="bg-gray-50 border-t border-gray-100">
                <td colspan="5" class="py-4 px-6 font-bold text-right uppercase tracking-widest text-[10px] text-gray-500">Total Keseluruhan</td>
                <td class="py-4 px-6 font-bold text-lg text-[#001f3f] text-right">Rp {{ number_format($total_keseluruhan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection