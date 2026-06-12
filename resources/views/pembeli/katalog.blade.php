@extends('layouts.pembeli')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4">
    <div class="mb-4 text-center">
        <h1 class="text-xl font-bold text-gray-800 uppercase tracking-[0.3em]">
            Katalog {{ isset($currentCategory) && $currentCategory != 'Semua' ? '— ' . $currentCategory : 'Produk' }}
        </h1>
        <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest">— Koleksi Rajutan Tangan —</p>
    </div>

    {{-- FITUR PENCARIAN PRODUK KRIYA RAJUT --}}
    <div class="max-w-md mx-auto mb-6 px-4">
        <form action="{{ url()->current() }}" method="GET" class="relative flex items-center border-b border-gray-300 focus-within:border-[#001f3f] transition-colors py-1">
            
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari produk rajutan..." 
                   class="w-full bg-transparent border-none p-0 text-xs font-medium uppercase tracking-widest text-gray-700 placeholder-gray-400 focus:ring-0">
            
            {{-- Tombol Reset (X) jika user sedang mencari sesuatu --}}
            @if(request('search'))
                <a href="{{ route('katalog', request()->route('category') ?? 'semua') }}" class="text-gray-400 hover:text-red-500 mr-2 text-xs">
                    ✕
                </a>
            @endif

            <button type="submit" class="text-gray-400 hover:text-[#001f3f] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>

    <div id="sticky-sensor" class="h-px w-full"></div>

    {{-- KATEGORI NAV DENGAN FITUR SLIDER HORIZONTAL (SUDAH DIPERBAIKI KE TENGAH) --}}
    <div id="nav-kategori" class="sticky top-[83px] z-[30] w-full transition-all duration-300">
        <div id="bg-kategori-wrapper" class="w-full border-t border-gray-100 transition-all duration-300 py-6">
            <div class="relative max-w-4xl mx-auto px-8 group/nav">
                
                {{-- Tombol Prev: Hanya muncul di mobile/HP jika menu kepotong --}}
                <button id="nav-prev" class="absolute left-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 hover:text-[#001f3f] hover:border-[#001f3f] transition-all opacity-0 group-hover/nav:opacity-100 z-10 shadow-sm md:hidden">
                    &larr;
                </button>

                {{-- Container Kategori: Ditambahkan md:justify-center agar otomatis ke tengah di desktop --}}
                <div id="container-kategori" class="flex items-center justify-start md:justify-center gap-6 md:gap-10 overflow-x-auto whitespace-nowrap scroll-smooth no-scrollbar px-2" style="-webkit-overflow-scrolling: touch;">
                    @foreach(['Semua', 'Pakaian', 'Aksesoris', 'Dekorasi', 'Amigurumi', 'Tas & Wadah'] as $kategori)
                    @php
                        $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $kategori));
                        $isActive = (isset($currentCategory) && $currentCategory == $kategori) || (!isset($currentCategory) && $kategori == 'Semua');
                    @endphp
                    <a href="{{ route('katalog', $slug) }}"
                       class="inline-block text-[11px] font-bold uppercase tracking-widest transition-all {{ $isActive ? 'text-[#001f3f] underline underline-offset-8 decoration-2' : 'text-gray-400 hover:text-[#001f3f]' }}">
                        {{ $kategori }}
                    </a>
                    @endforeach
                </div>

                {{-- Tombol Next: Hanya muncul di mobile/HP jika menu kepotong --}}
                <button id="nav-next" class="absolute right-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 hover:text-[#001f3f] hover:border-[#001f3f] transition-all opacity-0 group-hover/nav:opacity-100 z-10 shadow-sm md:hidden">
                    &rarr;
                </button>

                <div class="absolute right-6 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden"></div>
            </div>
        </div>
    </div>

    {{-- GRID PRODUK --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-16 mt-6">
        @forelse($produk as $i => $p)
        <div class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
            <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/' . $p->foto) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p->nama }}">
            </div>

            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">{{ $p->nama }}</h3>
            <p class="text-[9px] text-gray-400 my-2 uppercase tracking-[0.2em] px-1 flex items-center justify-center">{{ $p->kategori ?? 'Umum' }}</p>

            {{-- Tampilan Harga Termurah "Mulai Dari" --}}
            <p class="text-xs font-bold text-[#001f3f] mb-3">
                @if($p->variasis->count() > 0)
                    Rp {{ number_format($p->variasis->min('harga'), 0, ',', '.') }}
                @else
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                @endif
            </p>

            <button data-modal-target="modal-produk-{{ $i }}" data-modal-toggle="modal-produk-{{ $i }}"
                class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
                Lihat Detail
            </button>

            {{-- Modal Detail Produk Berbasis Relasi Variasi --}}
            <div id="modal-produk-{{ $i }}" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm container-modal-produk p-4">
                <div class="relative w-full max-w-3xl h-auto">
                    <div class="relative bg-white border border-gray-300 p-6 md:p-8 shadow-2xl">

                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $p->id }}">
                            <input type="hidden" name="nama" value="{{ $p->nama }}">
                            <input type="hidden" name="foto" value="{{ asset('images/' . $p->foto) }}">
                            <input type="hidden" name="deskripsi" value="{{ $p->deskripsi }}">
                            <input type="hidden" name="harga" class="input-harga-hidden" value="{{ $p->variasis->first()->harga ?? $p->harga }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                                
                                {{-- Slider Multi Images --}}
                                <div class="relative w-full aspect-square overflow-hidden border border-gray-100 bg-gray-50 group">
                                    <div class="flex transition-transform duration-500 ease-in-out h-full" id="slider-{{ $p->id }}">
                                        @if($p->fotos && $p->fotos->count() > 0)
                                            @foreach($p->fotos as $indexFoto => $foto)
                                                <div class="w-full h-full flex-shrink-0" data-index-foto="{{ $indexFoto }}">
                                                    <img src="{{ asset('images/' . $foto->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="w-full h-full flex-shrink-0">
                                                <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                                            </div>
                                        @endif
                                    </div>

                                    @if($p->fotos && $p->fotos->count() > 1)
                                        <button type="button" onclick="moveSlide('{{ $p->id }}', -1)" 
                                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-10 font-bold">
                                            &larr;
                                        </button>

                                        <button type="button" onclick="moveSlide('{{ $p->id }}', 1)" 
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all opacity-0 group-hover:opacity-100 z-10 font-bold">
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
                                    <div class="mb-4">
                                        <h2 class="text-xl font-bold text-gray-900 mb-1 uppercase tracking-tight">{{ $p->nama }}</h2>
                                        <p class="text-lg font-bold text-[#001f3f] text-display-harga">
                                            Rp {{ number_format($p->variasis->first()->harga ?? $p->harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <p class="text-[12px] text-gray-500 leading-relaxed mb-4 whitespace-pre-line">{{ $p->deskripsi }}</p>

                                    <div class="mb-4">
                                        <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Pilih Variasi Produk</span>
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($p->variasis as $keyVar => $v)
                                                @php
                                                    $fotoIndex = 0;
                                                    if($p->fotos && $v->foto) {
                                                        $fotoIndex = $p->fotos->pluck('foto')->search($v->foto);
                                                        if($fotoIndex === false) { $fotoIndex = 0; }
                                                    }
                                                @endphp

                                                <label class="cursor-pointer">
                                                    <input type="radio" name="produk_variasi_id" value="{{ $v->id }}" class="peer hidden radio-variasi" 
                                                    data-harga="{{ $v->harga }}" 
                                                    data-stok="{{ $v->stok }}"
                                                    data-index-foto="{{ $fotoIndex }}"
                                                    {{ $keyVar == 0 ? 'checked' : '' }}
                                                    {{ $v->stok == 0 ? 'disabled' : '' }}>
                                                    
                                                    <span class="block border border-gray-200 rounded-md px-3 py-1.5 text-[11px] font-medium text-gray-600 peer-checked:border-[#001f3f] peer-checked:text-[#001f3f] peer-checked:bg-blue-50/30 hover:bg-gray-50 transition-all peer-disabled:bg-gray-100 peer-disabled:text-gray-300 peer-disabled:border-gray-200 peer-disabled:cursor-not-allowed">
                                                        {{ $v->ukuran }} - {{ $v->warna }} 
                                                        @if($v->stok == 0) (Habis) @endif
                                                    </span>
                                                </label>
                                            @empty
                                                <span class="text-xs text-amber-500 font-bold">Variasi belum diatur oleh admin.</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mb-4 font-bold text-[10px]">
                                        <span class="text-gray-400 uppercase tracking-widest">Ketersediaan Varian:</span>
                                        <span class="badge-status-stok text-gray-800">Menghitung...</span>
                                    </div>

                                    <div class="mb-6">
                                        <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Jumlah</span>
                                        <div class="flex items-center w-28 border border-gray-300 rounded-full overflow-hidden h-8">
                                            <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-r border-gray-300 font-bold" onclick="this.parentNode.querySelector('.input-quantity').stepDown(); checkMaxQuantity(this);">–</button>
                                            <input type="number" name="quantity" value="1" min="1" class="w-full border-none p-0 text-center text-xs focus:ring-0 font-bold bg-transparent input-quantity">
                                            <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-l border-gray-300 font-bold" onclick="this.parentNode.querySelector('.input-quantity').stepUp(); checkMaxQuantity(this);">+</button>
                                        </div>
                                    </div>

                                    @if($p->variasis->count() > 0)
                                        <button type="submit" class="w-full btn-submit-keranjang bg-[#001f3f] text-white py-3.5 rounded-full text-center hover:bg-gray-800 transition-all font-bold text-[10px] uppercase tracking-widest shadow-md disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
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
        <div class="col-span-2 md:col-span-3 lg:col-span-5 py-20 text-center">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                {{ request('search') ? 'Produk "'.request('search').'" tidak ditemukan' : 'Belum ada produk di kategori ini' }}
            </p>
            @if(request('search'))
                <a href="{{ route('katalog', request()->route('category') ?? 'semua') }}" class="inline-block mt-4 text-[10px] text-[#001f3f] underline underline-offset-4 font-bold uppercase tracking-widest hover:text-gray-600">
                    Kembali ke Semua Produk
                </a>
            @endif
        </div>
        @endforelse
    </div>
</div>

{{-- STYLE HIDE SCROLLBAR --}}
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    const currentSlides = {};

    // Fungsi utama penggerak slide
    function goToSlide(productId, slideIndex) {
        const slider = document.getElementById(`slider-${productId}`);
        if (!slider) return;

        const totalSlides = slider.children.length;

        if (slideIndex >= totalSlides) slideIndex = 0;
        if (slideIndex < 0) slideIndex = totalSlides - 1;

        currentSlides[productId] = slideIndex;

        const percentage = -(slideIndex * 100);
        slider.style.transform = `translateX(${percentage}%)`;

        const dots = document.querySelectorAll(`.dot-${productId}`);
        dots.forEach((dot, index) => {
            if (index === slideIndex) {
                dot.classList.add('!bg-white', 'w-4');
            } else {
                dot.classList.remove('!bg-white', 'w-4');
            }
        });
    }

    // Fungsi tombol manual panah kiri-kanan
    function moveSlide(productId, direction) {
        if (currentSlides[productId] === undefined) {
            currentSlides[productId] = 0;
        }
        const nextSlide = currentSlides[productId] + direction;
        goToSlide(productId, nextSlide);
    }

    // Fungsi validasi batas kuantitas input
    function checkMaxQuantity(button) {
        const input = button.parentNode.querySelector('.input-quantity');
        const max = parseInt(input.getAttribute('max')) || 1;
        let current = parseInt(input.value);
        
        if (isNaN(current) || current < 1) {
            input.value = max > 0 ? 1 : 0;
        } else if (current > max) {
            input.value = max;
        }
    }

    // SCRIPT INTERAKSI SLIDER HORIZONTAL MENU KATEGORI
    document.getElementById('nav-next').addEventListener('click', function() {
        document.getElementById('container-kategori').scrollLeft += 150;
    });
    document.getElementById('nav-prev').addEventListener('click', function() {
        document.getElementById('container-kategori').scrollLeft -= 150;
    });

    // LOGIKA LIVE HARGA, STOK, & AUTOMATIC SLIDER ON VARIANT CLICK
    document.addEventListener('DOMContentLoaded', function () {
        const modals = document.querySelectorAll('.container-modal-produk');
        
        modals.forEach(modal => {
            const radios = modal.querySelectorAll('.radio-variasi');
            const displayHarga = modal.querySelector('.text-display-harga');
            const hiddenHarga = modal.querySelector('.input-harga-hidden');
            const badgeStok = modal.querySelector('.badge-status-stok');
            const inputQty = modal.querySelector('.input-quantity');
            const btnSubmit = modal.querySelector('.btn-submit-keranjang');
            
            const hiddenIdInput = modal.querySelector('input[name="id"]');
            const productId = hiddenIdInput ? hiddenIdInput.value : null;

            function updateModalState(isVariantChanged = false) {
                const selectedRadio = modal.querySelector('.radio-variasi:checked');
                
                if (selectedRadio) {
                    const harga = parseInt(selectedRadio.getAttribute('data-harga'));
                    const stok = parseInt(selectedRadio.getAttribute('data-stok'));
                    const targetFotoIndex = parseInt(selectedRadio.getAttribute('data-index-foto')) || 0;

                    displayHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                    hiddenHarga.value = harga;

                    if (isVariantChanged && productId) {
                        goToSlide(productId, targetFotoIndex);
                    }

                    inputQty.setAttribute('max', stok);
                    
                    if (isVariantChanged) {
                        inputQty.value = stok > 0 ? 1 : 0;
                    } else {
                        let currentVal = parseInt(inputQty.value);
                        if (currentVal > stok) inputQty.value = stok;
                        if ((isNaN(currentVal) || currentVal < 1) && stok > 0) inputQty.value = 1;
                        if (stok === 0) inputQty.value = 0;
                    }

                    if (stok > 0) {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stok} Pcs)</span>`;
                        if(btnSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.innerText = '+ Tambah ke Keranjang';
                        }
                    } else {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Varian Habis</span>`;
                        if(btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerText = 'Stok Varian Habis';
                        }
                    }
                }
            }

            if (inputQty) {
                inputQty.addEventListener('input', function() {
                    const max = parseInt(this.getAttribute('max')) || 0;
                    let current = parseInt(this.value);
                    if (current > max) this.value = max;
                });

                inputQty.addEventListener('blur', function() {
                    const max = parseInt(this.getAttribute('max')) || 0;
                    let current = parseInt(this.value);
                    if (isNaN(current) || current < 1) {
                        this.value = max > 0 ? 1 : 0;
                    }
                });
            }

            radios.forEach(radio => {
                radio.addEventListener('change', () => updateModalState(true));
            });

            updateModalState(false); 
        });

        // STICKY DETECTOR OBSERVER
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
        if(sensor) observer.observe(sensor);
    });
</script>
@endsection