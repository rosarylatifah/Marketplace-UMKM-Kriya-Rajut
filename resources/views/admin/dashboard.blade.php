@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Panel Admin</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Dashboard</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Selamat datang kembali, Admin.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-10">

    <a href="{{ route('admin.produk.index') }}" class="block">
        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 hover:shadow-md hover:border-blue-200 cursor-pointer">
            <p class="text-[10px] uppercase tracking-[0.25em] text-gray-400 mb-4">Produk Tersedia</p>
            <p class="text-4xl font-bold text-[#001f3f] mb-1">{{ $totalProduk }}</p>
            <p class="text-[11px] text-gray-400 font-medium">Total produk aktif</p>
        </div>
    </a>

    <a href="{{ route('admin.pesanan.konfirmasi') }}" class="block">
        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 hover:shadow-md hover:border-blue-200 cursor-pointer">
            <p class="text-[10px] uppercase tracking-[0.25em] text-gray-400 mb-4">Pesanan Baru</p>
            <p class="text-4xl font-bold text-[#001f3f] mb-1">{{ $pesananBaru }}</p>
            <p class="text-[11px] text-gray-400 font-medium">Menunggu Konfirmasi</p>
        </div>
    </a>

    <a href="{{ route('admin.pesanan.index') }}" class="block">
        <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 hover:shadow-md hover:border-blue-200 cursor-pointer">
            <p class="text-[10px] uppercase tracking-[0.25em] text-gray-400 mb-4">Pesanan Aktif</p>
            <p class="text-4xl font-bold text-[#001f3f] mb-1">{{ $pesananAktif }}</p>
            <p class="text-[11px] text-gray-400 font-medium">Sedang Diproses</p>
        </div>
    </a>
    
    <a href="{{ route('admin.pendapatan.bulanan') }}" class="block">
        <div class="bg-white border border-gray-200 rounded-xl p-7 transition-all duration-300 hover:shadow-md hover:border-blue-200 cursor-pointer flex flex-col justify-between h-full">
            <p class="text-[10px] uppercase tracking-[0.25em] text-gray-400 mb-4">Pendapatan Kotor Bulan Ini</p>
            <p class="text-2xl font-bold text-[#001f3f] mb-1">
                Rp {{ number_format($pendapatanKotorBulanIni, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-gray-400">
                <span class="text-green-600 font-medium">Murni produk</span> • Tidak termasuk ongkir
            </p>
        </div>
    </a>

</div>

{{-- Produk Terlaris --}}
<div class="bg-white border border-gray-200 rounded-xl p-8 mt-6 mb-10"> 
    
    <div class="flex justify-between items-center mb-2 border-b border-gray-100 pb-6">
        <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-widest">Produk Terlaris (Best Seller)</h2>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($produkTerlaris as $produk)
        <div class="flex justify-between items-center py-4">
            <span class="text-xs text-gray-700 font-medium">{{ $produk->nama_barang }}</span>
            <span class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-bold">
                {{ $produk->total_terjual }} Terjual
            </span>
        </div>
        @empty
        <p class="text-xs text-gray-400 italic">Belum ada data penjualan.</p>
        @endforelse
    </div>
</div>

{{-- Aktivitas Terbaru --}}
<div class="bg-white border border-gray-200 rounded-xl p-8">

    <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Riwayat</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Aktivitas Terbaru</h2>
        </div>
        <a href="/admin/lihat-semua" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-colors">
            Lihat Semua
        </a>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($aktivitas as $a)
        <div class="flex justify-between items-center py-4 hover:bg-[#F3F5F1] px-4 rounded-lg transition-colors duration-150">
            <div>
                <p class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $a->id_pesanan }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $a->nama_pembeli }} &nbsp;·&nbsp; Rp {{ number_format($a->total, 0, ',', '.') }}</p>
            </div>
            
            <div class="text-right">
                @if($a->status == 'BELUM KONFIRMASI')
                    <span class="inline-block bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Belum Dikonfirmasi</span>
                @elseif($a->status == 'SEDANG DIPROSES')
                    <span class="inline-block bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Sedang Diproses</span>
                @elseif($a->status == 'DALAM PERJALANAN')
                    <span class="inline-block bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Dalam Perjalanan</span>
                @elseif($a->status == 'DIBATALKAN')
                    <span class="inline-block bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Dibatalkan</span>
                @elseif($a->status == 'SELESAI')
                    <span class="inline-block bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Selesai</span>
                @endif
                <p class="text-[10px] text-gray-300 mt-1">{{ $a->created_at->format('d/m/Y') }}</p>
            </div>

        </div>
        @empty
        <div class="py-16 text-center">
            <i class="fa-solid fa-chart-simple text-3xl text-gray-200 mb-3"></i>
            <p class="text-sm text-gray-400 uppercase tracking-widest">Belum ada aktivitas</p>
        </div>
        @endforelse
    </div>

</div>

@endsection
