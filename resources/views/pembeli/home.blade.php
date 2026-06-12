@extends('layouts.pembeli')

@section('content')

{{-- 1. HERO BANNER SLIDER (OTOMATIS & MANUAL) --}}
<div class="relative bg-white w-screen left-1/2 -translate-x-1/2 border-b border-gray-100 overflow-hidden mb-12 shadow-sm">
    <div id="hero-slider" class="relative w-full h-[600px] md:h-[500px]">
        
        <div class="hero-slide absolute inset-0 w-full h-full flex flex-col md:flex-row items-center transition-opacity duration-1000 opacity-100 z-10">
            <div class="w-full md:w-1/2 p-12 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 bg-white h-full flex flex-col justify-center">
                <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-4 block">Est. 2026</span>
                <h1 class="text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-6 uppercase tracking-tight">
                    Eksplorasi <br> <span class="text-gray-400 font-light italic">Seni Rajut</span> <br> Modern.
                </h1>
                <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-8 uppercase tracking-widest">
                    Koleksi buatan tangan dengan benang premium untuk gaya hidup minimalis.
                </p>
                <div>
                    <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="w-full md:w-1/2 h-1/2 md:h-full relative bg-gray-50">
                <img src="{{ asset('images/hero1.jpg') }}" class="w-full h-full object-cover brightness-90 hover:grayscale-0 transition-all duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-white via-white/10 to-transparent"></div>
            </div>
        </div>

        <div class="hero-slide absolute inset-0 w-full h-full flex flex-col md:flex-row items-center transition-opacity duration-1000 opacity-0 z-0">
            <div class="w-full md:w-1/2 p-12 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 bg-white h-full flex flex-col justify-center">
                <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-4 block">100% Handmade</span>
                <h1 class="text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-6 uppercase tracking-tight">
                    Dibuat <br> <span class="text-gray-400 font-light italic">Dengan</span> <br> Ketelitian.
                </h1>
                <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-8 uppercase tracking-widest">
                    Setiap simpul rajutan memiliki cerita unik dan kualitas benang katun terbaik.
                </p>
                <div>
                    <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="w-full md:w-1/2 h-1/2 md:h-full relative bg-gray-50">
                <img src="{{ asset('images/hero2.jpg') }}" class="w-full h-full object-cover brightness-90 hover:grayscale-0 transition-all duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-white via-white/10 to-transparent"></div>
            </div>
        </div>

        <div class="absolute bottom-6 left-12 md:left-[max(2rem,calc((100vw-80rem)/2+2rem))] z-20 flex gap-2">
            <button class="dot-hero w-8 h-1 bg-[#001f3f] transition-all duration-300 rounded-full" onclick="setSlide(0)"></button>
            <button class="dot-hero w-3 h-1 bg-gray-300 transition-all duration-300 rounded-full" onclick="setSlide(1)"></button>
        </div>
    </div>
</div>

{{-- SECTION TITLE DENGAN NAVIGASI TOMBOL SLIDE PRODUK --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-[14px] font-bold text-[#001f3f] uppercase tracking-[0.3em]">Koleksi Pilihan</h2>
        <div class="h-[2px] w-12 bg-[#001f3f] mt-2"></div>
    </div>
    <div class="flex items-center gap-4">
        <a href="/katalog" class="text-[10px] font-bold text-gray-400 hover:text-[#001f3f] uppercase tracking-widest transition-colors">Lihat Semua &rarr;</a>
    </div>
</div>

{{-- 2. LIST PRODUK YANG BISA DISLIDE HORIZONTAL --}}
<div id="produk-slider-container" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-6 mb-16 no-scrollbar" style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
    @forelse($produk as $i => $p)
    <div class="snap-start min-w-[240px] max-w-[240px] md:min-w-[230px] md:max-w-[230px] flex-shrink-0 group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
        <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p->nama }}">
        </div>

        <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider line-clamp-1 w-full px-1">{{ $p->nama }}</h3>
        <p class="text-[9px] text-gray-400 my-2 uppercase tracking-[0.2em] px-1 flex items-center justify-center">{{ $p->kategori ?? 'Umum' }}</p>
        
        <p class="text-xs font-bold text-[#001f3f] mb-3">
            @if($p->variasis->count() > 0)
                Rp {{ number_format($p->variasis->min('harga'), 0, ',', '.') }}
            @else
                Rp {{ number_format($p->harga, 0, ',', '.') }}
            @endif
        </p>

        <button data-modal-target="modal-home-{{ $i }}" data-modal-toggle="modal-home-{{ $i }}"
            class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
            Lihat Detail
        </button>

        {{-- Modal Detail Produk (Variasi Dinamis ala Shopee) --}}
        <div id="modal-home-{{ $i }}" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/60 backdrop-blur-sm p-4 container-modal-produk">
            <div class="relative bg-white border border-gray-300 w-full max-w-3xl p-6 md:p-8 shadow-2xl">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}">
                    <input type="hidden" name="nama" value="{{ $p->nama }}">
                    <input type="hidden" name="foto" value="{{ asset('images/' . $p->foto) }}">
                    <input type="hidden" name="deskripsi" value="{{ $p->deskripsi }}">
                    <input type="hidden" name="harga" class="input-harga-hidden" value="{{ $p->variasis->first()->harga ?? $p->harga }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        
                        {{-- ================= MODAL MULTI IMAGE CAROUSEL SLIDER ================= --}}
                        <div class="relative w-full aspect-square overflow-hidden border border-gray-100 bg-gray-50 group">
                            <div class="flex transition-transform duration-500 ease-in-out h-full inner-slider-container" id="slider-home-{{ $p->id }}" data-current-index="0">
                                @if($p->fotos && $p->fotos->count() > 0)
                                    @foreach($p->fotos as $indexFoto => $foto)
                                        <div class="w-full h-full flex-shrink-0" data-index-foto="{{ $indexFoto }}">
                                            <img src="{{ asset('images/' . $foto->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="w-full h-full flex-shrink-0" data-index-foto="0">
                                        <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                                    </div>
                                @endif
                            </div>

                            @if($p->fotos && $p->fotos->count() > 1)
                                <button type="button" onclick="moveSlideHome('{{ $p->id }}', -1)" 
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all z-10 font-bold opacity-0 group-hover:opacity-100">
                                    &larr;
                                </button>

                                <button type="button" onclick="moveSlideHome('{{ $p->id }}', 1)" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all z-10 font-bold opacity-0 group-hover:opacity-100">
                                    &rarr;
                                </button>

                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 bg-black/20 px-2 py-1 rounded-full z-10">
                                    @foreach($p->fotos as $index => $foto)
                                        <span class="dot-home-{{ $p->id }} w-2 h-2 rounded-full bg-white/50 transition-all {{ $index === 0 ? '!bg-white w-4' : '' }}"></span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-xl font-bold uppercase tracking-tight text-gray-900 mb-1">{{ $p->nama }}</h2>
                            
                            <p class="text-lg font-bold text-[#001f3f] mb-4 text-display-harga">
                                Rp {{ number_format($p->variasis->first()->harga ?? $p->harga, 0, ',', '.') }}
                            </p>
                            
                            <p class="text-[12px] text-gray-500 leading-relaxed mb-4 whitespace-pre-line">{{ $p->deskripsi }}</p>

                            <div class="mb-4">
                                <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Pilih Variasi Produk</span>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($p->variasis as $keyVar => $v)
                                        @php
                                            // FIX KOREKSI: Mengganti $v->foto_varian menjadi $v->foto agar sinkron dengan database & katalog
                                            $fotoIndex = 0;
                                            if($p->fotos && $v->foto) {
                                                $fotoIndex = $p->fotos->pluck('foto')->search($v->foto);
                                                if($fotoIndex === false) { $fotoIndex = 0; }
                                            }
                                        @endphp
                                        <label class="cursor-pointer label-pilihan-variasi">
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

                            <button type="submit" class="w-full btn-submit-keranjang bg-[#001f3f] text-white py-3.5 rounded-full text-center hover:bg-gray-800 transition-all font-bold text-[10px] uppercase tracking-widest shadow-md disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
                <button data-modal-hide="modal-home-{{ $i }}" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors btn-tutup-modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-2 md:col-span-3 lg:col-span-5 w-full flex flex-col items-center justify-center py-24 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-[11px] text-gray-400 uppercase tracking-widest font-semibold">Belum ada produk yang ditambahkan</p>
    </div>
    @endforelse
</div>

{{-- CSS TAMBAHAN BUAT NGILANGIN SCROLLBAR JALUR BAWAH DI SLIDER --}}
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- SCRIPT SLIDER (HERO BANNER & PRODUK) --}}
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
    // FIX KOREKSI: Tambah pengecekan tombol agar tidak menyebabkan error crash JavaScript jika tombol tidak ada
    const btnSlideNext = document.getElementById('slide-next');
    if (btnSlideNext) {
        btnSlideNext.addEventListener('click', function() {
            const container = document.getElementById('produk-slider-container');
            container.scrollLeft += 260; 
        });
    }

    // ================= FUNGSI UTAMA PENGGERAK SLIDER FOTO DI MODAL =================
    function goToModalSlide(produkId, currentIndex) {
        const slider = document.getElementById('slider-home-' + produkId);
        if (!slider) return;

        const imageCount = slider.children.length;

        if (currentIndex >= imageCount) currentIndex = 0;
        if (currentIndex < 0) currentIndex = imageCount - 1;

        slider.setAttribute('data-current-index', currentIndex);
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Update indikator baris dot bawah slider modal
        const dotsModal = document.querySelectorAll(`.dot-home-${produkId}`);
        dotsModal.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.classList.add('!bg-white', 'w-4');
            } else {
                dot.classList.remove('!bg-white', 'w-4');
            }
        });
    }

    // Tombol manual panah kiri kanan modal
    function moveSlideHome(produkId, direction) {
        const slider = document.getElementById('slider-home-' + produkId);
        let currentIndex = parseInt(slider.getAttribute('data-current-index')) || 0;
        goToModalSlide(produkId, currentIndex + direction);
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
            
            const hiddenIdInput = modal.querySelector('input[name="id"]');
            const produkId = hiddenIdInput ? hiddenIdInput.value : null;

            function updateModalState(isVariantChanged = false) {
                const selectedRadio = modal.querySelector('.radio-variasi:checked');
                
                if (selectedRadio) {
                    const harga = parseInt(selectedRadio.getAttribute('data-harga'));
                    const stok = parseInt(selectedRadio.getAttribute('data-stok'));
                    const targetFotoIndex = parseInt(selectedRadio.getAttribute('data-index-foto')) || 0;

                    displayHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                    hiddenHarga.value = harga;

                    // Fitur geser foto otomatis ke varian yang dicari pas diklik pembeli
                    if (isVariantChanged && produkId) {
                        goToModalSlide(produkId, targetFotoIndex);
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