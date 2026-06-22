@extends('layouts.admin')

@section('content')

{{-- Header yang lebih lega --}}
<div class="mb-8 mt-4">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Panel Admin</p>
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Semua Aktivitas Pesanan</h1>
        <a href="/admin/dashboard" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-colors flex items-center gap-1">
            ← Kembali ke Dashboard
        </a>
    </div>
    <div class="mt-3 h-px w-16 bg-[#001f3f]"></div>
    
    {{-- Search Bar rapi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
        <p class="text-xs text-gray-400">Menampilkan seluruh riwayat transaksi Kriya Rajut.</p>
        
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID / Nama Pembeli..." 
                   class="text-xs border border-gray-200 px-4 py-2.5 rounded-lg outline-none focus:border-[#001f3f] transition-all w-full sm:w-64">
            <button type="submit" class="bg-[#001f3f] text-white text-xs font-bold uppercase tracking-wider px-6 py-2.5 rounded-lg hover:bg-[#002d5a] transition-all">
                Cari
            </button>
        </form>
    </div>
</div>

{{-- Tabel Container dengan shadow dan padding yang pas --}}
<div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] uppercase tracking-widest text-gray-400">
                    <th class="pb-6 pl-2 font-bold">ID Pesanan</th>
                    <th class="pb-6 font-bold">Tanggal</th>
                    <th class="pb-6 font-bold">Nama Pembeli</th>
                    <th class="pb-6 font-bold">Detail Barang</th>
                    <th class="pb-6 font-bold">Total</th>
                    <th class="pb-6 font-bold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($semua_pesanan as $p)
                <tr class="hover:bg-[#F3F5F1]/50 transition-colors duration-150">
                    <td class="py-5 pl-2 font-bold text-[#001f3f] uppercase tracking-wider">
                        {{-- Logika link halaman sesuai status --}}
                        @if($p->status == 'BELUM KONFIRMASI') 
                            <a href="/admin/pesanan-konfirmasi?search={{ $p->id_pesanan }}" class="hover:underline text-orange-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'SEDANG DIPROSES')
                            <a href="/admin/pesanan-masuk?search={{ $p->id_pesanan }}" class="hover:underline text-amber-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'DALAM PERJALANAN')
                            <a href="/admin/pesanan-masuk?search={{ $p->id_pesanan }}" class="hover:underline text-blue-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'SELESAI')
                            <a href="/admin/pesanan-selesai?search={{ $p->id_pesanan }}" class="hover:underline text-emerald-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'DIBATALKAN')
                            <a href="/admin/pesanan-batal?search={{ $p->id_pesanan }}" class="hover:underline text-rose-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'PENGAJUAN BATAL')
                            <a href="/admin/pesanan-pengajuan-batal?search={{ $p->id_pesanan }}" class="hover:underline text-600">#{{ $p->id_pesanan }}</a>
                        @else
                            <span class="text-gray-500">#{{ $p->id_pesanan }}</span>
                        @endif
                    </td>
                    <td class="py-5 text-gray-400">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="py-5 text-gray-700 font-medium">{{ $p->nama_pembeli }}</td>

                    <td class="py-5">
                        <div class="flex flex-col gap-1 max-w-[200px]">
                            @foreach(explode(',', $p->nama_barang) as $item)
                                <div class="text-gray-700 leading-tight">
                                    <span class="font-bold uppercase text-[10px]">{{ trim(Str::beforeLast(trim($item), '(')) }}</span>
                                    <span class="text-gray-400 text-[9px] lowercase">{{ preg_match('/\(([^)]+)\)$/', trim($item), $m) ? '('.$m[1].')' : '' }}</span>
                                    <span class="font-bold text-[#001f3f] text-[9px]">x{{ preg_match('/x\s*(\d+)$/', trim($item), $m) ? $m[1] : 1 }}</span>
                                </div>
                            @endforeach
                        </div>
                    </td>

                    <td class="py-5 font-semibold text-gray-800">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="py-5 text-center">
                        <span class="inline-block text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border 
                            {{ $p->status == 'BELUM KONFIRMASI' ? 'bg-orange-50 text-orange-600 border-orange-100' : '' }}
                            {{ $p->status == 'SEDANG DIPROSES' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }} 
                            {{ $p->status == 'DALAM PERJALANAN' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                            {{ $p->status == 'SELESAI' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                            {{ $p->status == 'DIBATALKAN' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}
                            {{ $p->status == 'PENGAJUAN BATAL' ? ' text-600 border-100' : '' }}">
                             
                            {{ $p->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center text-gray-400 uppercase tracking-widest italic">Belum ada riwayat aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection