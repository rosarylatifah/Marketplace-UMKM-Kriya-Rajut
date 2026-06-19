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
        <form action="{{ url()->current() }}" method="GET"
            class="relative flex items-center border-b border-gray-300 focus-within:border-[#001f3f] transition-colors py-1">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk rajutan..."
                class="w-full bg-transparent border-none p-0 text-xs font-medium uppercase tracking-widest text-gray-700 placeholder-gray-400 focus:ring-0">

            {{-- Tombol Reset (X) jika user sedang mencari sesuatu --}}
            @if(request('search'))
            <a href="{{ route('katalog', request()->route('category') ?? 'semua') }}"
                class="text-gray-400 hover:text-red-500 mr-2 text-xs">
                ✕
            </a>
            @endif

            <button type="submit" class="text-gray-400 hover:text-[#001f3f] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <div id="sticky-sensor" class="h-px w-full"></div>

    {{-- KATEGORI NAV DENGAN FITUR SLIDER HORIZONTAL (SUDAH DIPERBAIKI KE TENGAH) --}}
    <div id="nav-kategori" class="sticky top-[83px] z-[30] w-full transition-all duration-300">
        <div id="bg-kategori-wrapper"
            class="w-full full-bleed border-t border-transparent transition-all duration-300 py-3">
            <div class="relative max-w-4xl mx-auto px-8 group/nav">

                {{-- Tombol Prev --}}
                <button id="nav-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 hover:text-[#001f3f] hover:border-[#001f3f] transition-all opacity-0 group-hover/nav:opacity-100 z-10 shadow-sm md:hidden">
                    &larr;
                </button>

                {{-- Container Kategori --}}
                <div id="container-kategori"
                    class="flex items-center justify-start md:justify-center gap-6 md:gap-10 overflow-x-auto whitespace-nowrap scroll-smooth no-scrollbar px-2"
                    style="-webkit-overflow-scrolling: touch;">
                    @php $isActiveSemua = !isset($currentCategory) || $currentCategory == 'Semua'; @endphp
                    <a href="{{ route('katalog') }}"
                        class="inline-block text-[11px] font-bold uppercase tracking-widest transition-all {{ $isActiveSemua ? 'text-[#001f3f] underline underline-offset-8 decoration-2' : 'text-gray-400 hover:text-[#001f3f]' }}">
                        Semua
                    </a>

                    @foreach($kategoris as $k)
                    @php $isActive = isset($currentCategory) && $currentCategory == $k->nama; @endphp
                    <a href="{{ route('katalog', $k->slug) }}"
                        class="inline-block text-[11px] font-bold uppercase tracking-widest transition-all {{ $isActive ? 'text-[#001f3f] underline underline-offset-8 decoration-2' : 'text-gray-400 hover:text-[#001f3f]' }}">
                        {{ $k->nama }}
                    </a>
                    @endforeach
                </div>

                {{-- Tombol Next --}}
                <button id="nav-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 hover:text-[#001f3f] hover:border-[#001f3f] transition-all opacity-0 group-hover/nav:opacity-100 z-10 shadow-sm md:hidden">
                    &rarr;
                </button>

                <div
                    class="absolute right-6 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden">
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SORTING --}}
    <div class="max-w-4xl mx-auto mb-8 px-4">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center justify-center gap-4">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <div class="flex items-center gap-2">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Urutkan</label>
                <select name="urutkan" onchange="this.form.submit()"
                    class="text-[10px] font-bold uppercase tracking-widest border border-gray-200 rounded-full px-4 py-2 outline-none focus:border-[#001f3f] bg-white text-gray-700 cursor-pointer">
                    <option value="" {{ !request('urutkan') ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_asc" {{ request('urutkan') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah
                    </option>
                    <option value="harga_desc" {{ request('urutkan') == 'harga_desc' ? 'selected' : '' }}>Harga
                        Tertinggi</option>
                    <option value="nama_az" {{ request('urutkan') == 'nama_az' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Harga</label>
                <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="Min"
                    class="w-24 text-[11px] border border-gray-200 rounded-full px-4 py-2 outline-none focus:border-[#001f3f] bg-white text-gray-700 placeholder-gray-300">
                <span class="text-gray-300 text-xs">—</span>
                <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="Max"
                    class="w-24 text-[11px] border border-gray-200 rounded-full px-4 py-2 outline-none focus:border-[#001f3f] bg-white text-gray-700 placeholder-gray-300">
                <button type="submit"
                    class="bg-[#001f3f] hover:bg-[#003366] text-white text-[10px] font-bold uppercase tracking-widest px-5 py-2 rounded-full transition-all">
                    Terapkan
                </button>
            </div>

            @if(request('harga_min') || request('harga_max') || request('urutkan'))
            <a href="{{ url()->current() }}{{ request('search') ? '?search='.request('search') : '' }}"
                class="text-[10px] text-gray-400 hover:text-red-500 uppercase tracking-widest font-bold underline">
                Reset Filter
            </a>
            @endif
        </form>
    </div>

    {{-- GRID PRODUK --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-16 mt-6">
        @forelse($produk as $i => $p)
        <div
            class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
            <div
                class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/' . $p->foto) }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                    alt="{{ $p->nama }}">
            </div>

            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">{{ $p->nama }}</h3>
            <p class="text-[9px] text-gray-400 my-2 uppercase tracking-[0.2em] px-1 flex items-center justify-center">
                {{ $p->kategori ?? 'Umum' }}</p>

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
            <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">
                {{ request('search') ? 'Produk "'.request('search').'" tidak ditemukan' : 'Belum ada produk di kategori ini' }}
            </p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    #bg-kategori-wrapper {
        transition: background-color 0.3s ease, backdrop-filter 0.3s ease, border-color 0.3s ease;
    }

    .full-bleed {
        width: 100vw !important;
        position: relative;
        left: 50%;
        margin-left: -50vw;
    }
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

    let slideInterval = setInterval(nextSlide, 5000);

    // --- JAVASCRIPT LOGIC SCROLL HORIZONTAL PRODUK ---
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

                    if (isVariantChanged) {
                        inputQty.value = stok > 0 ? 1 : 0;
                    } else {
                        if (parseInt(inputQty.value) > stok) inputQty.value = stok;
                        if (parseInt(inputQty.value) < 1 && stok > 0) inputQty.value = 1;
                        if (stok === 0) inputQty.value = 0;
                    }

                    if (stok > 0) {
                        badgeStok.innerHTML =
                            `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stok} Pcs)</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.innerText = '+ Tambah ke Keranjang';
                        }
                    } else {
                        badgeStok.innerHTML =
                            `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Varian Habis</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerText = 'Stok Varian Habis';
                        }
                    }
                } else {
                    const stokDasar = parseInt(modal.getAttribute('data-stok-dasar')) || 0;
                    inputQty.setAttribute('max', stokDasar);
                    if (parseInt(inputQty.value) > stokDasar) inputQty.value = stokDasar;
                    if (parseInt(inputQty.value) < 1 && stokDasar > 0) inputQty.value = 1;
                    if (stokDasar === 0) inputQty.value = 0;

                    if (stokDasar > 0) {
                        badgeStok.innerHTML =
                            `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stokDasar} Pcs)</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.innerText = '+ Tambah ke Keranjang';
                        }
                    } else {
                        badgeStok.innerHTML =
                            `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Habis</span>`;
                        if (btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerText = 'Stok Habis';
                        }
                    }
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', () => updateModalState(true));
            });

            updateModalState(false);
        });
    });

    // --- LOGIC SCROLL NAV STICKY ---
    window.addEventListener('scroll', function () {
        const wrapper = document.getElementById('bg-kategori-wrapper');
        const sensor = document.getElementById('sticky-sensor');
        const scrollPosition = window.scrollY;

        if (scrollPosition >= sensor.offsetTop) {
            wrapper.classList.add('bg-white/80', 'backdrop-blur-md', 'border-gray-100');
            wrapper.classList.remove('bg-transparent', 'border-transparent');
        } else {
            wrapper.classList.remove('bg-white/80', 'backdrop-blur-md', 'border-gray-100');
            wrapper.classList.add('bg-transparent', 'border-transparent');
        }
    });
</script>
@endsection