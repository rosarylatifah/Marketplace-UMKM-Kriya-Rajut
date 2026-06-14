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
            @include('pembeli.detail_popup', ['p' => $p, 'i' => $i, 'idPrefix' => 'modal-produk'])
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
    // --- JAVASCRIPT LOGIC BANNER SLIDER ATAS ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.dot-hero');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.replace('opacity-0', 'opacity-100');
                slide.classList.add('z-10');
                slide.classList.remove('z-0');
            } else {
                slide.classList.replace('opacity-100', 'opacity-0');
                slide.classList.add('z-0');
                slide.classList.remove('z-10');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.replace('w-3', 'w-8');
                dot.classList.replace('bg-gray-300', 'bg-[#001f3f]');
            } else {
                dot.classList.replace('w-8', 'w-3');
                dot.classList.replace('bg-[#001f3f]', 'bg-gray-300');
            }
        });
        currentSlide = index;
    }

    function nextSlide() {
        let next = (currentSlide + 1) % totalSlides;
        showSlide(next);
    }

    function setSlide(index) {
        showSlide(index);
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000); 
    }

    let slideInterval = setInterval(nextSlide, 5000); 

    // --- JAVASCRIPT LOGIC SCROLL HORIZONTAL PRODUK ---
    const btnSlideNext = document.getElementById('slide-next');
    if (btnSlideNext) {
        btnSlideNext.addEventListener('click', function() {
            const container = document.getElementById('produk-slider-container');
            container.scrollLeft += 260; 
        });
    }

    // ================= FUNGSI UTAMA PENGGERAK SLIDER FOTO DI MODAL (DINAMIS) =================
    function moveSlidePopup(sliderId, dotClass, direction) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;

        const totalSlides = slider.children.length;
        let currentIndex = parseInt(slider.getAttribute('data-current-index')) || 0;

        currentIndex += direction;

        if (currentIndex >= totalSlides) currentIndex = 0;
        if (currentIndex < 0) currentIndex = totalSlides - 1;

        slider.setAttribute('data-current-index', currentIndex);
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Update tampilan titik penunjuk (dots)
        const dots = document.querySelectorAll('.' + dotClass);
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('!bg-white', 'w-4');
            } else {
                dot.classList.remove('!bg-white', 'w-4');
            }
        });
    }

    // --- JAVASCRIPT LIVE UPDATE HARGA, STOK, & AUTO SLIDE PAS KLIK VARIASI ---
    document.addEventListener('DOMContentLoaded', function () {
        const modals = document.querySelectorAll('.container-modal-produk');
        
        modals.forEach(modal => {
            const radios = modal.querySelectorAll('.radio-variasi');
            const displayHarga = modal.querySelector('.text-display-harga');
            const hiddenHarga = modal.querySelector('.input-harga-hidden');
            const badgeStok = modal.querySelector('.badge-status-stok');
            const inputQty = modal.querySelector('.input-quantity');
            const btnSubmit = modal.querySelector('.btn-submit-keranjang');
            
            function updateModalState(isVariantChanged = false) {
                const selectedRadio = modal.querySelector('.radio-variasi:checked');
                
                if (selectedRadio) {
                    const harga = parseInt(selectedRadio.getAttribute('data-harga'));
                    const stok = parseInt(selectedRadio.getAttribute('data-stok'));
                    const targetFotoIndex = parseInt(selectedRadio.getAttribute('data-index-foto')) || 0;
                    const sliderId = selectedRadio.getAttribute('data-slider-id');
                    const dotClass = selectedRadio.getAttribute('data-dot-class');

                    displayHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                    hiddenHarga.value = harga;

                    // FIX KOREKSI: Pemicu slide foto otomatis yang dinamis memakai data-attributes komponen
                    if (isVariantChanged && sliderId) {
                        const slider = document.getElementById(sliderId);
                        if (slider) {
                            slider.setAttribute('data-current-index', targetFotoIndex);
                            slider.style.transform = `translateX(-${targetFotoIndex * 100}%)`;
                            
                            const dots = document.querySelectorAll('.' + dotClass);
                            dots.forEach((dot, index) => {
                                if (index === targetFotoIndex) {
                                    dot.classList.add('!bg-white', 'w-4');
                                } else {
                                    dot.classList.remove('!bg-white', 'w-4');
                                }
                            });
                        }
                    }

                    inputQty.setAttribute('max', stok);
                    
                    if (isVariantChanged) {
                        inputQty.value = stok > 0 ? 1 : 0;
                    } else {
                        if (parseInt(inputQty.value) > stok) inputQty.value = stok;
                        if (parseInt(inputQty.value) < 1 && stok > 0) inputQty.value = 1;
                        if (stok === 0) inputQty.value = 0;
                    }

                    if (stok > 0) {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stok} Pcs)</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.innerText = '+ Tambah ke Keranjang';
                        }
                    } else {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Varian Habis</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerText = 'Stok Varian Habis';
                        }
                    }
                } else {
                    badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Habis</span>`;
                    if (btnSubmit) btnSubmit.disabled = true;
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
    });

    function checkMaxQuantity(button) {
        const input = button.parentNode.querySelector('.input-quantity');
        const max = parseInt(input.getAttribute('max')) || 1;
        const current = parseInt(input.value);
        if (current > max) input.value = max;
        if (current < 1 && max > 0) input.value = 1;
    }
</script>
@endsection