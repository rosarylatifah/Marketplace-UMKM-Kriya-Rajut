@extends('layouts.pembeli')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4">
    <div class="mb-4 text-center">
        <h1 class="text-xl font-bold text-gray-800 uppercase tracking-[0.3em]">
            Katalog {{ isset($currentCategory) && $currentCategory != 'Semua' ? '— ' . $currentCategory : 'Produk' }}
        </h1>
        <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest">— Koleksi Rajutan Tangan —</p>
    </div>

    <div id="sticky-sensor" class="h-px w-full"></div>

    <div id="nav-kategori" class="sticky top-[83px] z-[30] w-full transition-all duration-300">
        <div id="bg-kategori-wrapper" class="w-full border-t border-gray-100 transition-all duration-300 py-6">
            <div id="container-kategori" class="flex flex-wrap justify-center gap-4 md:gap-8 max-w-7xl mx-auto px-6 transition-all duration-300">
                @foreach(['Semua', 'Pakaian', 'Aksesoris', 'Dekorasi', 'Amigurumi', 'Tas & Wadah'] as $kategori)
                @php
                    $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $kategori));
                    $isActive = (isset($currentCategory) && $currentCategory == $kategori) || (!isset($currentCategory) && $kategori == 'Semua');
                @endphp
                <a href="{{ route('katalog', $slug) }}"
                   class="text-[11px] font-bold uppercase tracking-widest transition-colors {{ $isActive ? 'text-[#001f3f] underline underline-offset-8' : 'text-gray-500 hover:text-[#001f3f]' }}">
                    {{ $kategori }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-16 mt-6">
        @forelse($produk as $i => $p)
        <div class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
            <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-50 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/' . $p->foto) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p->nama }}">
            </div>

            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">{{ $p->nama }}</h3>
            <p class="text-[10px] text-gray-400 my-2 italic px-2 leading-relaxed line-clamp-2">{{ $p->deskripsi }}</p>

            <button data-modal-target="modal-produk-{{ $i }}" data-modal-toggle="modal-produk-{{ $i }}"
                class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
                Lihat Detail
            </button>

            {{-- Modal Detail Produk --}}
            <div id="modal-produk-{{ $i }}" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full bg-black/60 backdrop-blur-sm">
                <div class="relative p-4 w-full max-w-3xl h-auto">
                    <div class="relative bg-white border border-gray-300 p-6 md:p-8 shadow-2xl">

                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id"        value="{{ $p->id }}">
                            <input type="hidden" name="nama"      value="{{ $p->nama }}">
                            <input type="hidden" name="harga"     value="{{ $p->harga }}">
                            <input type="hidden" name="foto"      value="{{ asset('images/' . $p->foto) }}">
                            <input type="hidden" name="deskripsi" value="{{ $p->deskripsi }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                                <div class="aspect-square border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                                </div>

                                <div class="flex flex-col">
                                    <div class="mb-4">
                                        <h2 class="text-xl font-bold text-gray-900 mb-1 uppercase tracking-tight">{{ $p->nama }}</h2>
                                        <p class="text-lg font-bold text-gray-700">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                                    </div>

                                    <p class="text-[12px] text-gray-500 leading-relaxed mb-6">{{ $p->deskripsi }}</p>

                                    <div class="flex items-center gap-2 mb-6">
                                        @if($p->stok > 0)
                                            <span class="px-2 py-0.5 bg-green-50 text-green-700 text-[9px] font-bold uppercase tracking-wider border border-green-100">Tersedia ({{ $p->stok }})</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[9px] font-bold uppercase tracking-wider border border-red-100">Stok Habis</span>
                                        @endif
                                    </div>

                                    <div class="mb-6">
                                        <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Jumlah</span>
                                        <div class="flex items-center w-28 border border-gray-300 rounded-full overflow-hidden h-8">
                                            <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-r border-gray-300 font-bold" onclick="this.parentNode.querySelector('input').stepDown()">-</button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $p->stok }}" class="w-full border-none p-0 text-center text-xs focus:ring-0 font-bold bg-transparent">
                                            <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-l border-gray-300 font-bold" onclick="this.parentNode.querySelector('input').stepUp()">+</button>
                                        </div>
                                    </div>

                                    @if($p->stok > 0)
                                    <button type="submit" class="w-full bg-[#001f3f] text-white py-3.5 rounded-full text-center hover:bg-gray-800 transition-all font-bold text-[10px] uppercase tracking-widest shadow-md">
                                        + Tambah ke Keranjang
                                    </button>
                                    @else
                                    <button type="button" disabled class="w-full bg-gray-200 text-gray-400 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <button data-modal-hide="modal-produk-{{ $i }}" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-5 py-20 text-center">
            <i class="fa-solid fa-box-open text-4xl text-gray-200 mb-4"></i>
            <p class="text-sm text-gray-400 uppercase tracking-widest">Belum ada produk di kategori ini</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bgWrapper = document.getElementById('bg-kategori-wrapper');
        const sensor = document.getElementById('sticky-sensor');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const isStuck = entry.boundingClientRect.top < 70;
                if (isStuck) {
                    bgWrapper.style.backgroundColor = "white";
                    bgWrapper.style.boxShadow = "0 2px 4px rgba(0,0,0,0.05)";
                    bgWrapper.style.width = "100vw";
                    bgWrapper.style.position = "relative";
                    bgWrapper.style.left = "50%";
                    bgWrapper.style.marginLeft = "-50vw";
                    bgWrapper.classList.replace('py-6', 'py-3');
                } else {
                    bgWrapper.style.backgroundColor = "transparent";
                    bgWrapper.style.boxShadow = "none";
                    bgWrapper.style.width = "100%";
                    bgWrapper.style.left = "0";
                    bgWrapper.style.marginLeft = "0";
                    bgWrapper.classList.replace('py-3', 'py-6');
                }
            });
        }, { rootMargin: '-70px 0px 0px 0px', threshold: [0, 1] });
        observer.observe(sensor);
    });
</script>
@endsection