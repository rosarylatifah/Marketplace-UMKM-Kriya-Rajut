@extends('layouts.admin')

@section('content')

<<<<<<< HEAD
{{-- Header yang lebih lega --}}
<div class="mb-8 mt-4">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Panel Admin</p>
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
=======
{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Panel Admin</p>
    <div class="flex justify-between items-center">
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Semua Aktivitas Pesanan</h1>
        <a href="/admin/dashboard" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-colors flex items-center gap-1">
            ← Kembali ke Dashboard
        </a>
    </div>
<<<<<<< HEAD
    <div class="mt-3 h-px w-16 bg-[#001f3f]"></div>
    
    {{-- Search Bar rapi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
        <p class="text-xs text-gray-400">Menampilkan seluruh riwayat transaksi Kriya Rajut.</p>
        
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID / Nama Pembeli..." 
                   class="text-xs border border-gray-200 px-4 py-2.5 rounded-lg outline-none focus:border-[#001f3f] transition-all w-full sm:w-64">
            <button type="submit" class="bg-[#001f3f] text-white text-xs font-bold uppercase tracking-wider px-6 py-2.5 rounded-lg hover:bg-[#002d5a] transition-all">
=======
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    
    {{-- Baris Teks dan Form Search Sebaris (Kiri & Kanan) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-3">
        <p class="text-sm text-gray-400">Menampilkan seluruh riwayat transaksi Kriya Rajut.</p>
        
        {{-- Form Pencarian Sapu Jagat ditaruh di sebelah kanan --}}
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID / Nama Pembeli / Status..." 
                   class="text-xs border border-gray-200 px-4 py-2 rounded-lg outline-none focus:border-[#001f3f] transition-all min-w-[220px] w-full sm:w-auto">
            <button type="submit" class="bg-[#001f3f] text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:opacity-90 transition-all shrink-0">
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                Cari
            </button>
        </form>
    </div>
</div>

<<<<<<< HEAD
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
=======
{{-- Tabel Riwayat Aktivitas --}}
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-400">
                    <th class="pb-4 font-bold">ID Pesanan</th>
                    <th class="pb-4 font-bold">Tanggal</th>
                    <th class="pb-4 font-bold">Nama Pembeli</th>
                    <th class="pb-4 font-bold">Detail Barang</th>
                    <th class="pb-4 font-bold">Total Pembayaran</th>
                    <th class="pb-4 font-bold text-center">Status</th>
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($semua_pesanan as $p)
                <tr class="hover:bg-[#F3F5F1]/50 transition-colors duration-150">
<<<<<<< HEAD
                    <td class="py-5 pl-2 font-bold text-[#001f3f] uppercase tracking-wider">
                        {{-- Logika link lo tetep aman disini --}}
                        @if($p->status == 'BELUM KONFIRMASI')
                            <a href="/admin/pesanan-konfirmasi?search={{ $p->id_pesanan }}" class="hover:underline text-blue-600">#{{ $p->id_pesanan }}</a>
                        @elseif(in_array($p->status, ['SEDANG DIPROSES', 'DALAM PERJALANAN']))
                            <a href="/admin/pesanan-masuk?search={{ $p->id_pesanan }}" class="hover:underline text-amber-600">#{{ $p->id_pesanan }}</a>
                        @elseif($p->status == 'SELESAI')
                            <a href="/admin/pesanan-selesai?search={{ $p->id_pesanan }}" class="hover:underline text-emerald-600">#{{ $p->id_pesanan }}</a>
=======
                    
                    {{-- 🌟 LINK INTERAKTIF: Mengarahkan halaman tujuan otomatis + auto sorot keyword --}}
                    <td class="py-4 font-bold text-[#001f3f] uppercase tracking-wider align-middle">
                        @if($p->status == 'BELUM KONFIRMASI')
                            <a href="/admin/pesanan-konfirmasi?search={{ $p->id_pesanan }}" class="hover:underline text-blue-600 flex items-center gap-1">
                                #{{ $p->id_pesanan }} <span class="text-[9px] font-normal text-gray-400">↗</span>
                            </a>
                        @elseif($p->status == 'SEDANG DIPROSES' || $p->status == 'DALAM PERJALANAN')
                            <a href="/admin/pesanan-masuk?search={{ $p->id_pesanan }}" class="hover:underline text-amber-600 flex items-center gap-1">
                                #{{ $p->id_pesanan }} <span class="text-[9px] font-normal text-gray-400">↗</span>
                            </a>
                        @elseif($p->status == 'SELESAI')
                            <a href="/admin/pesanan-selesai?search={{ $p->id_pesanan }}" class="hover:underline text-emerald-600 flex items-center gap-1">
                                #{{ $p->id_pesanan }} <span class="text-[9px] font-normal text-gray-400">↗</span>
                            </a>
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                        @else
                            <span class="text-gray-500">#{{ $p->id_pesanan }}</span>
                        @endif
                    </td>
<<<<<<< HEAD
                    <td class="py-5 text-gray-400">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="py-5 text-gray-700 font-medium">{{ $p->nama_pembeli }}</td>
                    
                    <td class="py-5">
                        <div class="flex flex-col gap-1 max-w-[200px]">
                            @foreach(explode(',', $p->nama_barang) as $item)
                                <div class="text-gray-700 leading-tight">
                                    <span class="font-bold uppercase text-[10px]">{{ trim(Str::beforeLast(trim($item), '(')) }}</span>
                                    <span class="text-gray-400 text-[9px] lowercase">{{ preg_match('/\(([^)]+)\)$/', trim($item), $m) ? '('.$m[1].')' : '' }}</span>
                                    <span class="font-bold text-[#001f3f] text-[9px]">x{{ preg_match('/x\s*(\d+)$/', trim($item), $m) ? $m[1] : 1 }}</span>
=======

                    <td class="py-4 text-gray-400 align-middle">{{ $p->created_at->format('d/m/Y H:i') }} WIB</td>
                    <td class="py-4 text-gray-700 font-medium align-middle">{{ $p->nama_pembeli }}</td>
                    
                    {{-- Detail Barang --}}
                    <td class="py-4 align-middle">
                        <div class="flex flex-col gap-1.5 items-start justify-center max-w-xs">
                            @php
                                $items = explode(',', $p->nama_barang);
                            @endphp

                            @foreach($items as $item)
                                @php
                                    $item = trim($item);
                                    
                                    // 1. Ambil Kuantitas
                                    $qty = 1;
                                    if (preg_match('/\(x\s*(\d+)\)/', $item, $match)) {
                                        $qty = $match[1];
                                    } elseif (preg_match('/x\s*(\d+)$/', $item, $match)) {
                                        $qty = $match[1];
                                    }

                                    // Hilangkan bagian quantity dari string utama
                                    $clean_item = preg_replace('/\(x\s*\d+\)/', '', $item);
                                    $clean_item = preg_replace('/\s*x\s*\d+$/', '', $clean_item);
                                    $clean_item = trim($clean_item);

                                    // 2. Ambil Variasi
                                    $variasi = null;
                                    if (preg_match('/\(([^)]+)\)$/', $clean_item, $match)) {
                                        $variasi = trim($match[1]);
                                    }

                                    // 3. Ambil Nama Produk Utama
                                    $nama_produk = $variasi ? trim(Str::beforeLast($clean_item, '(')) : $clean_item;
                                @endphp

                                {{-- Teks Polosan Berbaris Kebawah --}}
                                <div class="text-left text-gray-700 leading-relaxed break-words w-full">
                                    <span class="font-bold uppercase text-[11px]">{{ $nama_produk }}</span>
                                    @if($variasi)
                                        <span class="text-gray-400 text-[10px] lowercase">({{ $variasi }})</span>
                                    @endif
                                    <span class="font-bold text-[#001f3f] text-[10px] ml-1">x{{ $qty }}</span>
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                                </div>
                            @endforeach
                        </div>
                    </td>

<<<<<<< HEAD
                    <td class="py-5 font-semibold text-gray-800">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="py-5 text-center">
                        <span class="inline-block {{ $p->status == 'SELESAI' ? 'bg-emerald-50 text-emerald-600' : ($p->status == 'DIBATALKAN' ? 'bg-rose-50 text-rose-600' : 'bg-gray-50 text-gray-600') }} text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-gray-100">
                            {{ $p->status }}
                        </span>
=======
                    <td class="py-4 font-semibold text-gray-800 align-middle">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="py-4 text-center align-middle">
                        @if($p->status == 'SEDANG DIPROSES')
                            <span class="inline-block bg-amber-50 text-amber-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-amber-100">Diproses</span>
                        @elseif($p->status == 'DALAM PERJALANAN')
                            <span class="inline-block bg-blue-50 text-blue-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-blue-100">Dikirim</span>
                        @elseif($p->status == 'DIBATALKAN')
                            <span class="inline-block bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Dibatalkan</span>
                        @elseif($p->status == 'SELESAI')
                            <span class="inline-block bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-emerald-100">Selesai</span>
                        @else
                            <span class="inline-block bg-gray-50 text-gray-500 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-gray-100">{{ $p->status }}</span>
                        @endif
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                    </td>
                </tr>
                @empty
                <tr>
<<<<<<< HEAD
                    <td colspan="6" class="py-16 text-center text-gray-400 uppercase tracking-widest italic">Belum ada riwayat aktivitas.</td>
=======
                    <td colspan="6" class="py-16 text-center text-gray-400 uppercase tracking-widest">
                        <i class="fa-solid fa-folder-open text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada riwayat aktivitas pesanan.
                    </td>
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection