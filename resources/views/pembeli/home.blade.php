@extends('layouts.pembeli')

@section('content')
<div class="relative bg-white w-screen left-1/2 -translate-x-1/2 border-b border-gray-100 overflow-hidden mb-12 shadow-sm">
    <div class="flex flex-col md:flex-row items-center">
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

        <div class="md:w-1/2 h-[500px] relative">
            <img src="https://images.unsplash.com/photo-1608408881647-773665673100?q=80&w=1000&auto=format&fit=crop"
                 class="w-full h-full object-cover grayscale brightness-90 hover:grayscale-0 transition-all duration-1000">
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/10 to-transparent"></div>
        </div>
    </div>
</div>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-[14px] font-bold text-[#001f3f] uppercase tracking-[0.3em]">Koleksi Pilihan</h2>
        <div class="h-[2px] w-12 bg-[#001f3f] mt-2"></div>
    </div>
    <a href="/katalog" class="text-[10px] font-bold text-gray-400 hover:text-[#001f3f] uppercase tracking-widest transition-colors">Lihat Semua Katalog &rarr;</a>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-16">
    @forelse($produk as $i => $p)
    <div class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
        <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p->nama }}">
        </div>

        <h3 class="font-bold text-gray-800 text-[13px] uppercase tracking-wider">{{ $p->nama }}</h3>
        <p class="text-[12px] font-bold text-gray-400 my-2 tracking-wide">
            Rp {{ number_format($p->harga, 0, ',', '.') }}
        </p>

        <button data-modal-target="modal-home-{{ $i }}" data-modal-toggle="modal-home-{{ $i }}"
            class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
            Lihat Detail
        </button>

        {{-- Modal --}}
        <div id="modal-home-{{ $i }}" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/60 backdrop-blur-sm p-4">
            <div class="relative bg-white w-full max-w-3xl p-6 md:p-8">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id"        value="{{ $p->id }}">
                    <input type="hidden" name="nama"      value="{{ $p->nama }}">
                    <input type="hidden" name="harga"     value="{{ $p->harga }}">
                    <input type="hidden" name="foto"      value="{{ asset('images/' . $p->foto) }}">
                    <input type="hidden" name="deskripsi" value="{{ $p->deskripsi }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        
                        {{-- PERUBAHAN UTAMA: Slider Foto Produk Multi Images --}}
                        <div class="relative w-full aspect-square overflow-hidden border border-gray-100 group">
                            <div class="flex transition-transform duration-500 ease-in-out h-full" id="slider-{{ $p->id }}">
                                @if($p->fotos && $p->fotos->count() > 0)
                                    @foreach($p->fotos as $foto)
                                        <div class="w-full h-full flex-shrink-0">
                                            <img src="{{ asset('images/' . $foto->nama_foto) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="w-full h-full flex-shrink-0">
                                        <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                            </div>

                            @if($p->fotos && $p->fotos->count() > 1)
                                <button type="button" onclick="moveSlide('{{ $p->id }}', -1)" 
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-10">
                                    &larr;
                                </button>

                                <button type="button" onclick="moveSlide('{{ $p->id }}', 1)" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-10">
                                    &rarr;
                                </button>

                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 bg-black/20 px-2 py-1 rounded-full z-10">
                                    @foreach($p->fotos as $index => $foto)
                                        <span class="dot-{{ $p->id }} w-2 h-2 rounded-full bg-white/50 transition-all {{ $index === 0 ? '!bg-white w-4' : '' }}"></span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-xl font-bold uppercase">{{ $p->nama }}</h2>
                            <p class="text-lg font-bold text-gray-700 mb-4">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                            <p class="text-[12px] text-gray-500 mb-4">{!! nl2br(e($p->deskripsi)) !!}</p>

                            <div class="flex items-center gap-2 mb-4">
                                @if($p->stok > 0)
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 text-[9px] font-bold uppercase tracking-wider border border-green-100">Tersedia ({{ $p->stok }})</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[9px] font-bold uppercase tracking-wider border border-red-100">Stok Habis</span>
                                @endif
                            </div>

                            <div class="mb-6">
                                <span class="text-[10px] font-bold block mb-2 uppercase">Jumlah</span>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $p->stok }}" class="w-20 border-gray-300 rounded-full text-center text-xs font-bold">
                            </div>

                            @if($p->stok > 0)
                            <button type="submit" class="w-full bg-[#001f3f] text-white py-3.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
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
                <button data-modal-hide="modal-home-{{ $i }}" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-5 py-20 text-center">
        <i class="fa-solid fa-box-open text-4xl text-gray-200 mb-4"></i>
        <p class="text-sm text-gray-400 uppercase tracking-widest">Belum ada produk tersedia</p>
    </div>
    @endforelse
</div>

{{-- JAVASCRIPT SLIDER --}}
<script>
    const currentSlides = {};

    function moveSlide(productId, direction) {
        const slider = document.getElementById(`slider-${productId}`);
        const totalSlides = slider.children.length;

        if (currentSlides[productId] === undefined) {
            currentSlides[productId] = 0;
        }

        currentSlides[productId] += direction;

        if (currentSlides[productId] >= totalSlides) {
            currentSlides[productId] = 0;
        } else if (currentSlides[productId] < 0) {
            currentSlides[productId] = totalSlides - 1;
        }

        const percentage = -(currentSlides[productId] * 100);
        slider.style.transform = `translateX(${percentage}%)`;

        const dots = document.querySelectorAll(`.dot-${productId}`);
        dots.forEach((dot, index) => {
            if (index === currentSlides[productId]) {
                dot.classList.add('!bg-white', 'w-4');
            } else {
                dot.classList.remove('!bg-white', 'w-4');
            }
        });
    }
</script>
@endsection