@extends('layouts.pembeli')

@section('content')

{{-- 1. HERO BANNER SLIDER --}}
<div class="relative bg-white w-screen left-1/2 -translate-x-1/2 border-b border-gray-100 overflow-hidden mb-10 shadow-sm">
    <div id="hero-slider" class="relative w-full h-[520px] sm:h-[520px] md:h-[500px]">

        <div class="hero-slide absolute inset-0 w-full h-full flex flex-col md:flex-row items-center transition-opacity duration-1000 opacity-100 z-10">
            <div class="w-full md:w-1/2 px-6 py-8 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 bg-white h-full flex flex-col justify-center">
                <span class="text-[11px] uppercase tracking-[0.4em] text-gray-400 mb-4 block">Est. 2026</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-5 uppercase tracking-tight">
                    Eksplorasi <br> <span class="text-gray-400 font-light italic">Seni Rajut</span> <br> Modern.
                </h1>
                <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-7 uppercase tracking-wider">
                    Koleksi buatan tangan dengan benang premium untuk gaya hidup minimalis.
                </p>
                <div>
                    <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="w-full md:w-1/2 h-[200px] sm:h-[220px] md:h-full relative bg-gray-50">
                <img src="{{ asset('images/hero1.jpg') }}" class="w-full h-full object-cover brightness-90 hover:grayscale-0 transition-all duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-white via-white/10 to-transparent"></div>
            </div>
        </div>

        <div class="hero-slide absolute inset-0 w-full h-full flex flex-col md:flex-row items-center transition-opacity duration-1000 opacity-0 z-0">
            <div class="w-full md:w-1/2 px-6 py-8 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 bg-white h-full flex flex-col justify-center">
                <span class="text-[11px] uppercase tracking-[0.4em] text-gray-400 mb-4 block">100% Handmade</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-5 uppercase tracking-tight">
                    Dibuat <br> <span class="text-gray-400 font-light italic">Dengan</span> <br> Ketelitian.
                </h1>
                <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-7 uppercase tracking-wider">
                    Setiap simpul rajutan memiliki cerita unik dan kualitas benang katun terbaik.
                </p>
                <div>
                    <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="w-full md:w-1/2 h-[200px] sm:h-[220px] md:h-full relative bg-gray-50">
                <img src="{{ asset('images/hero2.jpg') }}" class="w-full h-full object-cover brightness-90 hover:grayscale-0 transition-all duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-white via-white/10 to-transparent"></div>
            </div>
        </div>

        <div class="absolute bottom-5 left-6 md:left-[max(2rem,calc((100vw-80rem)/2+2rem))] z-20 flex gap-2">
            <button class="dot-hero w-8 h-1 bg-[#001f3f] transition-all duration-300 rounded-full" onclick="setSlide(0)"></button>
            <button class="dot-hero w-3 h-1 bg-gray-300 transition-all duration-300 rounded-full" onclick="setSlide(1)"></button>
        </div>
    </div>
</div>

{{-- SECTION TITLE --}}
<div class="mb-6 flex items-center justify-between px-1">
    <div>
        <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.3em]">Koleksi Pilihan</h2>
        <div class="h-[2px] w-10 bg-[#001f3f] mt-2"></div>
    </div>
    <a href="/katalog" class="text-[11px] font-bold text-gray-400 hover:text-[#001f3f] uppercase tracking-widest transition-colors">Lihat Semua &rarr;</a>
</div>

{{-- GRID PRODUK --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-16">
    @forelse($produk as $i => $p)
    <div class="w-full group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
        <div class="w-full bg-gray-50 aspect-square mb-3 border border-gray-100 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p->nama }}">
        </div>
        <h3 class="font-bold text-gray-800 text-[11px] sm:text-xs uppercase tracking-wide line-clamp-2 w-full px-1 leading-snug">{{ $p->nama }}</h3>
        <p class="text-[10px] text-gray-400 my-1.5 uppercase tracking-wider px-1">
    {{ $kategoriMap[$p->kategori] ?? $p->kategori ?? 'Umum' }}
</p>
        <p class="text-xs font-bold text-[#001f3f] mb-3">
            @if($p->variasis->count() > 0)
                Rp {{ number_format($p->variasis->min('harga'), 0, ',', '.') }}
            @else
                Rp {{ number_format($p->harga, 0, ',', '.') }}
            @endif
        </p>
        <button data-modal-target="modal-home-{{ $i }}" data-modal-toggle="modal-home-{{ $i }}"
            class="mt-auto w-full bg-[#001f3f] text-white text-[10px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
            Lihat Detail
        </button>
        @include('pembeli.detail_popup', ['p' => $p, 'i' => $i, 'idPrefix' => 'modal-home'])
    </div>
    @empty
    <div class="col-span-full w-full flex flex-col items-center justify-center py-24 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Belum ada produk yang ditambahkan</p>
    </div>
    @endforelse
</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.dot-hero');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.replace('opacity-0', 'opacity-100');
                slide.classList.add('z-10'); slide.classList.remove('z-0');
            } else {
                slide.classList.replace('opacity-100', 'opacity-0');
                slide.classList.add('z-0'); slide.classList.remove('z-10');
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

    function nextSlide() { showSlide((currentSlide + 1) % totalSlides); }
    function setSlide(index) { showSlide(index); clearInterval(slideInterval); slideInterval = setInterval(nextSlide, 5000); }
    let slideInterval = setInterval(nextSlide, 5000);

    function moveSlidePopup(sliderId, dotClass, direction) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;
        const total = parseInt(slider.getAttribute('data-total')) || slider.children.length;
        let current = parseInt(slider.getAttribute('data-current-index')) || 0;
        current += direction;
        if (current >= total) current = 0;
        if (current < 0) current = total - 1;
        _applySlidePopup(slider, dotClass, current, total);
    }

    function goToSlidePopup(sliderId, dotClass, targetIndex) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;
        const total = parseInt(slider.getAttribute('data-total')) || slider.children.length;
        let index = parseInt(targetIndex) || 0;
        _applySlidePopup(slider, dotClass, index, total);
    }

    function _applySlidePopup(slider, dotClass, index, total) {
        slider.setAttribute('data-current-index', index);
        slider.style.transform = `translateX(-${(index / total) * 100}%)`;
        document.querySelectorAll('.' + dotClass).forEach((dot, i) => {
            dot.style.width = i === index ? '1rem' : '0.5rem';
            dot.style.opacity = i === index ? '1' : '0.5';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('change', function (e) {
            const radio = e.target;
            if (!radio.classList.contains('radio-variasi')) return;
            const sliderId = radio.getAttribute('data-slider-id');
            const dotClass = radio.getAttribute('data-dot-class');
            const fotoIndex = radio.getAttribute('data-index-foto');
            if (sliderId && dotClass && fotoIndex !== null) goToSlidePopup(sliderId, dotClass, fotoIndex);
            const harga = radio.getAttribute('data-harga');
            if (harga) {
                const modal = radio.closest('.container-modal-produk');
                if (modal) {
                    const displayHarga = modal.querySelector('.text-display-harga');
                    const hiddenHarga = modal.querySelector('.input-harga-hidden');
                    if (displayHarga) displayHarga.textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                    if (hiddenHarga) hiddenHarga.value = harga;
                }
            }
            const stok = parseInt(radio.getAttribute('data-stok')) || 0;
            const modal = radio.closest('.container-modal-produk');
            if (modal) {
                const badge = modal.querySelector('.badge-status-stok');
                if (badge) { badge.textContent = stok > 0 ? `Stok: ${stok}` : 'Habis'; badge.style.color = stok > 0 ? '#374151' : '#ef4444'; }
            }
        });

        document.querySelectorAll('.radio-variasi:checked').forEach(function (radio) {
            const stok = parseInt(radio.getAttribute('data-stok')) || 0;
            const modal = radio.closest('.container-modal-produk');
            if (modal) {
                const badge = modal.querySelector('.badge-status-stok');
                if (badge) { badge.textContent = stok > 0 ? `Stok: ${stok}` : 'Habis'; badge.style.color = stok > 0 ? '#374151' : '#ef4444'; }
            }
        });
    });

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
                    displayHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                    hiddenHarga.value = harga;
                    inputQty.setAttribute('max', stok);
                    if (isVariantChanged) { inputQty.value = stok > 0 ? 1 : 0; }
                    else {
                        if (parseInt(inputQty.value) > stok) inputQty.value = stok;
                        if (parseInt(inputQty.value) < 1 && stok > 0) inputQty.value = 1;
                        if (stok === 0) inputQty.value = 0;
                    }
                    if (stok > 0) {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stok} Pcs)</span>`;
                        if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.innerText = '+ Tambah ke Keranjang'; }
                    } else {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Varian Habis</span>`;
                        if (btnSubmit) { btnSubmit.disabled = true; btnSubmit.innerText = 'Stok Varian Habis'; }
                    }
                } else {
                    const stokDasar = parseInt(modal.getAttribute('data-stok-dasar')) || 0;
                    inputQty.setAttribute('max', stokDasar);
                    if (parseInt(inputQty.value) > stokDasar) inputQty.value = stokDasar;
                    if (parseInt(inputQty.value) < 1 && stokDasar > 0) inputQty.value = 1;
                    if (stokDasar === 0) inputQty.value = 0;
                    if (stokDasar > 0) {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stokDasar} Pcs)</span>`;
                        if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.innerText = '+ Tambah ke Keranjang'; }
                    } else {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Habis</span>`;
                        if (btnSubmit) btnSubmit.disabled = true;
                    }
                }
            }

            if (inputQty) {
                inputQty.addEventListener('input', function() {
                    const max = parseInt(this.getAttribute('max')) || 0;
                    if (parseInt(this.value) > max) this.value = max;
                });
                inputQty.addEventListener('blur', function() {
                    const max = parseInt(this.getAttribute('max')) || 0;
                    if (isNaN(parseInt(this.value)) || parseInt(this.value) < 1) this.value = max > 0 ? 1 : 0;
                });
            }

            radios.forEach(radio => { radio.addEventListener('change', () => updateModalState(true)); });
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
