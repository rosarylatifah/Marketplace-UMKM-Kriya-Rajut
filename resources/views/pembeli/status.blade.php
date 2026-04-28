@extends('layouts.pembeli')

@section('content')
<div class="py-12 px-2">

    {{-- Header --}}
    <div class="mb-10">
        <h1 class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mb-2">Pesananmu</h1>
        <p class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Status Pesanan</p>
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    </div>

    {{-- Daftar Pesanan --}}
    <div class="space-y-4 mb-14">

        {{-- Item 1 --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center justify-between gap-6 hover:shadow-sm transition-shadow duration-200">
            <div class="flex items-center gap-6 flex-1 min-w-0">
                <div class="w-20 h-20 flex-shrink-0 bg-[#F3F5F1] border border-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest text-center leading-relaxed">Foto<br>Produk</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-1">No. #ORD-0012</p>
                    <h3 class="font-bold text-[#001f3f] text-base tracking-wide truncate">Tas Rajut Boboho</h3>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <p class="text-xs text-emerald-600 font-medium italic">Dalam Perjalanan</p>
                    </div>
                </div>
            </div>
            <a href="https://wa.me/6285778092881" target="_blank"
                class="flex-shrink-0 flex items-center gap-2 border border-gray-300 bg-white hover:bg-[#001f3f] hover:text-white hover:border-[#001f3f] text-gray-700 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-200">
                <i class="fab fa-whatsapp text-sm"></i> Kontak Penjual
            </a>
        </div>

        {{-- Item 2 --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center justify-between gap-6 hover:shadow-sm transition-shadow duration-200">
            <div class="flex items-center gap-6 flex-1 min-w-0">
                <div class="w-20 h-20 flex-shrink-0 bg-[#F3F5F1] border border-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest text-center leading-relaxed">Foto<br>Produk</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-1">No. #ORD-0013</p>
                    <h3 class="font-bold text-[#001f3f] text-base tracking-wide truncate">Dompet Mini Rajut</h3>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <p class="text-xs text-emerald-600 font-medium italic">Dalam Perjalanan</p>
                    </div>
                </div>
            </div>
            <a href="https://wa.me/6285778092881" target="_blank"
                class="flex-shrink-0 flex items-center gap-2 border border-gray-300 bg-white hover:bg-[#001f3f] hover:text-white hover:border-[#001f3f] text-gray-700 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-200">
                <i class="fab fa-whatsapp text-sm"></i> Kontak Penjual
            </a>
        </div>

    </div>

    {{-- Tombol Mulai Belanja --}}
    <div class="text-center">
        <a href="{{ route('katalog') }}"
            class="inline-flex items-center gap-3 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.25em] text-[11px] px-12 py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fa-solid fa-bag-shopping text-sm"></i>
            Mulai Belanja
        </a>
    </div>

</div>
@endsection