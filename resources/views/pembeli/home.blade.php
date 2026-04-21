@extends('layouts.pembeli')

@section('content')
{{-- Hero Section: Full Width --}}
<div class="relative bg-white w-screen left-1/2 -translate-x-1/2 border-b border-gray-100 overflow-hidden mb-12 shadow-sm">
    <div class="flex flex-col md:flex-row items-center">
        {{-- Sisi Teks --}}
        <div class="md:w-1/2 p-12 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 z-10">
            <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-4 block">Est. 2026</span>
            <h1 class="text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-6 uppercase tracking-tight">
                Eksplorasi <br> <span class="text-gray-400 font-light italic">Seni Rajut</span> <br> Modern.
            </h1>
            <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-8 uppercase tracking-widest">
                Koleksi buatan tangan dengan benang premium untuk gaya hidup minimalis.
            </p>
            <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                Lihat Koleksi
            </a>
        </div>
        
        {{-- Sisi Gambar --}}
        <div class="md:w-1/2 h-[500px] md:h-[500px] relative">
            <img src="https://images.unsplash.com/photo-1608408881647-773665673100?q=80&w=1000&auto=format&fit=crop" 
                 class="w-full h-full object-cover grayscale brightness-90 hover:grayscale-0 transition-all duration-1000" alt="Hero Rajut">
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/10 to-transparent"></div>
        </div>
    </div>
</div>

{{-- Label Section --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-[14px] font-bold text-[#001f3f] uppercase tracking-[0.3em]">Koleksi Pilihan</h2>
        <div class="h-[2px] w-12 bg-[#001f3f] mt-2"></div>
    </div>
    <a href="/katalog" class="text-[10px] font-bold text-gray-400 hover:text-[#001f3f] uppercase tracking-widest transition-colors">Lihat Semua Katalog &rarr;</a>
</div>

{{-- Grid Produk - 5 Kolom --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-16">
    @for ($i = 1; $i <= 15; $i++) 
    <div class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
        <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
            <img src="https://plus.unsplash.com/premium_photo-1678120610667-27a944670c79?q=80&w=400&auto=format&fit=crop&sig={{$i}}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="tas rajut">
        </div>
        
        <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">Tas Rajut {{ $i }}</h3>
        <p class="text-[10px] text-gray-400 my-2 italic px-2 leading-relaxed">Handmade premium quality.</p>
        
        <button data-modal-target="modal-produk-{{ $i }}" data-modal-toggle="modal-produk-{{ $i }}"
            class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
            Lihat Detail
        </button>

        {{-- Modal Detail Produk --}}
        <div id="modal-produk-{{ $i }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full bg-black/60 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-3xl h-auto">
                <div class="relative bg-white border border-gray-300 p-6 md:p-8 shadow-2xl">
                    
                    {{-- FORM STARTS HERE --}}
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        {{-- Data Produk Tersembunyi --}}
                        <input type="hidden" name="id" value="{{ $i }}">
                        <input type="hidden" name="nama" value="Tas Rajut {{ $i }}">
                        <input type="hidden" name="harga" value="150000">
                        <input type="hidden" name="foto" value="https://plus.unsplash.com/premium_photo-1678120610667-27a944670c79?q=80&w=400&auto=format&fit=crop&sig={{$i}}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                            {{-- Sisi Kiri: Foto --}}
                            <div class="aspect-square border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
                                <img src="https://plus.unsplash.com/premium_photo-1678120610667-27a944670c79?q=80&w=400&auto=format&fit=crop&sig={{$i}}" class="w-full h-full object-cover" alt="Detail Produk">
                            </div>

                            {{-- Sisi Kanan: Detail --}}
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <h2 class="text-xl font-bold text-gray-900 mb-1 uppercase tracking-tight">Tas Rajut Premium {{ $i }}</h2>
                                    <p class="text-lg font-bold text-gray-700">Rp 150.000</p>
                                </div>
                                
                                <p class="text-[12px] text-gray-500 leading-relaxed mb-6">
                                    Dibuat khusus secara handmade dengan presisi menggunakan benang premium. Cocok untuk menunjang penampilan estetik harianmu.
                                </p>

                                <div class="flex items-center gap-2 mb-6">
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-[9px] font-bold uppercase tracking-wider border border-green-100">Tersedia</span>
                                    <span class="text-[10px] text-gray-400">(10 pcs)</span>
                                </div>

                                <div class="mb-6">
                                    <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Jumlah</span>
                                    <div class="flex items-center w-28 border border-gray-300 rounded-full overflow-hidden h-8">
                                        <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-r border-gray-300 font-bold transition-colors" onclick="this.parentNode.querySelector('input').stepDown()">-</button>
                                        <input type="number" name="quantity" value="1" min="1" 
                                            class="w-full border-none p-0 text-center text-xs focus:ring-0 font-bold bg-transparent">
                                        <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-l border-gray-300 font-bold transition-colors" onclick="this.parentNode.querySelector('input').stepUp()">+</button>
                                    </div>
                                </div>

                                {{-- Tombol Submit Form --}}
                                <button type="submit" 
                                    class="w-full bg-[#001f3f] text-white py-3.5 rounded-full text-center hover:bg-gray-800 transition-all font-bold text-[10px] uppercase tracking-widest shadow-md">
                                    + Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- FORM ENDS HERE --}}
                    
                    {{-- Close Button --}}
                    <button data-modal-hide="modal-produk-{{ $i }}" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endfor
</div>
@endsection