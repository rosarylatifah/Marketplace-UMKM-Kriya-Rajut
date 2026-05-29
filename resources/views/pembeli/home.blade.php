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

        <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">{{ $p->nama }}</h3>
        <p class="text-[10px] text-gray-400 my-2 italic line-clamp-2 px-1">{{ $p->deskripsi ?? 'Handmade premium quality.' }}</p>

        {{-- Mengambil harga terendah dari variasi sebagai harga "Mulai Dari" --}}
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
                    
                    {{-- Hidden input buat nampung harga variasi yang aktif biar masuk ke backend cart --}}
                    <input type="hidden" name="harga" class="input-harga-hidden" value="{{ $p->variasis->first()->harga ?? $p->harga }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="aspect-square border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/' . $p->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama }}">
                        </div>
                        <div class="flex flex-col">
                            <h2 class="text-xl font-bold uppercase tracking-tight text-gray-900 mb-1">{{ $p->nama }}</h2>
                            
                            {{-- Harga Dinamis (Bakal berubah via JavaScript di bawah) --}}
                            <p class="text-lg font-bold text-[#001f3f] mb-4 text-display-harga">
                                Rp {{ number_format($p->variasis->first()->harga ?? $p->harga, 0, ',', '.') }}
                            </p>
                            
                            <p class="text-[12px] text-gray-500 leading-relaxed mb-4">{{ $p->deskripsi }}</p>

                            {{-- Pilihan Variasi Gabungan Ukuran & Warna --}}
                            <div class="mb-4">
                                <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Pilih Variasi Produk</span>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($p->variasis as $keyVar => $v)
                                        <label class="cursor-pointer label-pilihan-variasi">
                                            <input type="radio" name="produk_variasi_id" value="{{ $v->id }}" class="peer hidden radio-variasi" 
                                                   data-harga="{{ $v->harga }}" 
                                                   data-stok="{{ $v->stok }}"
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

                            {{-- Live Badge Info Stok Tergantung Varian --}}
                            <div class="flex items-center gap-2 mb-4 font-bold text-[10px]">
                                <span class="text-gray-400 uppercase tracking-widest">Ketersediaan Varian:</span>
                                <span class="badge-status-stok text-gray-800">Menghitung...</span>
                            </div>

                            {{-- Pengatur Jumlah Beli --}}
                            <div class="mb-6">
                                <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Jumlah</span>
                                <div class="flex items-center w-28 border border-gray-300 rounded-full overflow-hidden h-8">
                                    <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-r border-gray-300 font-bold" onclick="this.parentNode.querySelector('.input-quantity').stepDown(); checkMaxQuantity(this);">–</button>
                                    <input type="number" name="quantity" value="1" min="1" class="w-full border-none p-0 text-center text-xs focus:ring-0 font-bold bg-transparent input-quantity">
                                    <button type="button" class="w-8 h-full flex items-center justify-center hover:bg-gray-100 border-l border-gray-300 font-bold" onclick="this.parentNode.querySelector('.input-quantity').stepUp(); checkMaxQuantity(this);">+</button>
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
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
    <div class="col-span-5 py-20 text-center">
        <i class="fa-solid fa-box-open text-4xl text-gray-200 mb-4"></i>
        <p class="text-sm text-gray-400 uppercase tracking-widest">Belum ada produk tersedia</p>
    </div>
    @endforelse
</div>

{{-- JAVASCRIPT LIVE UPDATE HARGA & STOK PER VARIAN --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle logika dinamis untuk setiap modal produk
        const modals = document.querySelectorAll('.container-modal-produk');
        
        modals.forEach(modal => {
            const radios = modal.querySelectorAll('.radio-variasi');
            const displayHarga = modal.querySelector('.text-display-harga');
            const hiddenHarga = modal.querySelector('.input-harga-hidden');
            const badgeStok = modal.querySelector('.badge-status-stok');
            const inputQty = modal.querySelector('.input-quantity');
            const btnSubmit = modal.querySelector('.btn-submit-keranjang');

            function updateModalState() {
                // Cari radio button variasi mana yang lagi ke-check aktif
                const selectedRadio = modal.querySelector('.radio-variasi:checked');
                
                if (selectedRadio) {
                    const harga = parseInt(selectedRadio.getAttribute('data-harga'));
                    const stok = parseInt(selectedRadio.getAttribute('data-stok'));

                    // 1. Ganti tampilan harga live di layar & sync input hidden
                    displayHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
                    hiddenHarga.value = harga;

                    // 2. Set rule pembatasan angka maksimal input kuantitas belanja
                    inputQty.setAttribute('max', stok);
                    if (parseInt(inputQty.value) > stok) {
                        inputQty.value = stok;
                    }
                    if (stok === 0) inputQty.value = 0;

                    // 3. Update Text Badge Stok Varian
                    if (stok > 0) {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 rounded">Tersedia (${stok} Pcs)</span>`;
                        btnSubmit.disabled = false;
                        btnSubmit.innerText = '+ Tambah ke Keranjang';
                    } else {
                        badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Varian Habis</span>`;
                        btnSubmit.disabled = true;
                        btnSubmit.innerText = 'Stok Varian Habis';
                    }
                } else {
                    // Fallback kalau produk bener-bener gak punya variasi sama sekali
                    badgeStok.innerHTML = `<span class="px-2 py-0.5 bg-red-50 text-red-500 border border-red-100 rounded">Stok Habis</span>`;
                    btnSubmit.disabled = true;
                }
            }

            // Pasang event handler klik ke seluruh opsi variasi di dalam modal ini
            radios.forEach(radio => {
                radio.addEventListener('change', updateModalState);
            });

            // Jalankan trigger kalkulasi pertama kali pas modal ke-load
            updateModalState();
        });
    });

    // Validasi pencegahan manual via keyboard input quantity melebihi batas stok varian
    function checkMaxQuantity(button) {
        const input = button.parentNode.querySelector('.input-quantity');
        const max = parseInt(input.getAttribute('max')) || 1;
        const current = parseInt(input.value);
        if (current > max) {
            input.value = max;
        }
        if (current < 1 && max > 0) {
            input.value = 1;
        }
    }
</script>
@endsection