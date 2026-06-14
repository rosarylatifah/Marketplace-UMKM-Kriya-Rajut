@php
    $modalId  = isset($idPrefix) ? $idPrefix . '-' . $i : 'modal-produk-' . $i;
    $sliderId = 'slider-popup-' . $p->id . '-' . ($idPrefix ?? 'modal');
    $dotClass = 'dot-popup-' . $p->id . '-' . ($idPrefix ?? 'modal');

    // Kumpulkan semua foto
    $semuaFoto = [];

    // 1. Foto utama produk
    if (!empty($p->foto)) {
        $semuaFoto[] = $p->foto;
    }

    // 2. Foto galeri tambahan (Mendukung lazy-load otomatis jika eager-load tidak dipanggil)
    if ($p->fotos && $p->fotos->isNotEmpty()) {
        foreach ($p->fotos as $f) {
            if (!empty($f->nama_foto) && !in_array($f->nama_foto, $semuaFoto)) {
                $semuaFoto[] = $f->nama_foto;
            }
        }
    }

    // 3. Foto dari variasi
    if ($p->variasis && $p->variasis->isNotEmpty()) {
        foreach ($p->variasis as $v) {
            if (!empty($v->foto) && !in_array($v->foto, $semuaFoto)) {
                $semuaFoto[] = $v->foto;
            }
        }
    }

    $totalSlide = max(count($semuaFoto), 1);
@endphp

<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm container-modal-produk p-4">
    <div class="relative w-full max-w-3xl h-auto">
        <div class="relative bg-white border border-gray-300 p-6 md:p-8 shadow-2xl">

            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $p->id }}">
                <input type="hidden" name="nama" value="{{ $p->nama }}">
                {{-- PERBAIKAN: Hanya mengirimkan nama filenya saja, bukan asset() lengkap --}}
                <input type="hidden" name="foto" value="{{ $p->foto }}">
                <input type="hidden" name="deskripsi" value="{{ $p->deskripsi }}">
                <input type="hidden" name="harga" class="input-harga-hidden"
                    value="{{ $p->variasis->first()->harga ?? $p->harga }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">

                    {{-- ===== SLIDER GAMBAR ===== --}}
                    <div class="relative w-full aspect-square overflow-hidden border border-gray-100 bg-gray-50 group">

                        <div class="slider-track h-full"
                            id="{{ $sliderId }}"
                            data-current-index="0"
                            data-total="{{ $totalSlide }}"
                            style="
                                display: flex;
                                width: {{ $totalSlide * 100 }}%;
                                transition: transform 0.5s ease-in-out;
                                will-change: transform;
                            ">

                            @forelse($semuaFoto as $indexFoto => $pathFoto)
                                <div class="h-full flex-shrink-0"
                                    style="width: calc(100% / {{ $totalSlide }});"
                                    data-index-foto="{{ $indexFoto }}">
                                    <img src="{{ asset('images/' . $pathFoto) }}"
                                        class="w-full h-full object-cover"
                                        alt="{{ $p->nama }} - foto {{ $indexFoto + 1 }}"
                                        loading="lazy">
                                </div>
                            @empty
                                <div class="h-full flex-shrink-0" style="width: 100%;">
                                    <img src="{{ asset('images/default.jpg') }}"
                                        class="w-full h-full object-cover" alt="No Image">
                                </div>
                            @endforelse

                        </div>

                        {{-- Navigasi panah (hanya tampil jika > 1 foto) --}}
                        @if($totalSlide > 1)
                            <button type="button"
                                onclick="moveSlidePopup('{{ $sliderId }}', '{{ $dotClass }}', -1)"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all z-10 opacity-0 group-hover:opacity-100"
                                aria-label="Foto sebelumnya">&#8592;</button>
                            <button type="button"
                                onclick="moveSlidePopup('{{ $sliderId }}', '{{ $dotClass }}', 1)"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-[#001f3f] w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all z-10 opacity-0 group-hover:opacity-100"
                                aria-label="Foto berikutnya">&#8594;</button>

                            {{-- Dots indikator --}}
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 bg-black/20 px-2 py-1 rounded-full z-10 items-center">
                                @foreach($semuaFoto as $index => $pathFoto)
                                    <span class="{{ $dotClass }} block rounded-full transition-all duration-300 h-2 {{ $index === 0 ? 'w-4 bg-white opacity-100' : 'w-2 bg-white/50 opacity-50' }}"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ===== DETAIL PRODUK ===== --}}
                    <div class="flex flex-col">
                        <h2 class="text-xl font-bold uppercase tracking-tight text-gray-900 mb-1">{{ $p->nama }}</h2>
                        <p class="text-lg font-bold text-[#001f3f] mb-4 text-display-harga">
                            Rp {{ number_format($p->variasis->first()->harga ?? $p->harga, 0, ',', '.') }}
                        </p>
                        <p class="text-[12px] text-gray-500 leading-relaxed mb-4 whitespace-pre-line">{{ $p->deskripsi }}</p>

                        {{-- Pilih Variasi --}}
                        <div class="mb-4">
                            <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Pilih Variasi</span>
                            <div class="flex flex-wrap gap-2">
                                @forelse($p->variasis as $keyVar => $v)
                                    @php
                                        // Temukan index foto varian ini di $semuaFoto
                                        $fotoIndex = (!empty($v->foto)) ? array_search($v->foto, $semuaFoto) : false;
                                        if ($fotoIndex === false) $fotoIndex = 0;
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="radio" name="produk_variasi_id" value="{{ $v->id }}"
                                            class="peer hidden radio-variasi"
                                            data-harga="{{ $v->harga }}"
                                            data-stok="{{ $v->stok }}"
                                            data-index-foto="{{ $fotoIndex }}"
                                            data-slider-id="{{ $sliderId }}"
                                            data-dot-class="{{ $dotClass }}"
                                            {{ $keyVar == 0 ? 'checked' : '' }}
                                            {{ $v->stok == 0 ? 'disabled' : '' }}>
                                        <span class="block border border-gray-200 rounded-md px-3 py-1.5 text-[11px] font-medium text-gray-600 peer-checked:border-[#001f3f] peer-checked:bg-blue-50/30">
                                            {{ $v->ukuran }} {{ $v->warna }} {{ $v->stok == 0 ? '(Habis)' : '' }}
                                        </span>
                                    </label>
                                @empty
                                    <span class="text-xs text-amber-500">Variasi belum diatur.</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-4 font-bold text-[10px]">
                            <span class="text-gray-400 uppercase tracking-widest">Status:</span>
                            <span class="badge-status-stok text-gray-800">Menghitung...</span>
                        </div>

                        {{-- Jumlah --}}
                        <div class="mb-6">
                            <span class="text-[10px] text-gray-800 block mb-2 font-bold uppercase tracking-widest">Jumlah</span>
                            <div class="flex items-center w-28 border border-gray-300 rounded-full h-8">
                                <button type="button" class="w-8 h-full hover:bg-gray-100 rounded-l-full"
                                    onclick="this.parentNode.querySelector('.input-quantity').stepDown(); checkMaxQuantity(this);">–</button>
                                <input type="number" name="quantity" value="1" min="1"
                                    class="w-full border-none p-0 text-center text-xs font-bold bg-transparent input-quantity">
                                <button type="button" class="w-8 h-full hover:bg-gray-100 rounded-r-full"
                                    onclick="this.parentNode.querySelector('.input-quantity').stepUp(); checkMaxQuantity(this);">+</button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full btn-submit-keranjang bg-[#001f3f] text-white py-3.5 rounded-full text-center font-bold text-[10px] uppercase tracking-widest">
                            + Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </form>

            <button data-modal-hide="{{ $modalId }}"
                class="absolute top-4 right-4 text-gray-400 hover:text-red-500" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>
</div>

<script>
// Memastikan fungsi hanya dideklarasikan sekali di global scope walaupun di-looping
if (typeof moveSlidePopup !== 'function') {
    function moveSlidePopup(sliderId, dotClass, direction) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;
        
        const dots = document.querySelectorAll('.' + dotClass);
        let currentIndex = parseInt(slider.getAttribute('data-current-index')) || 0;
        const totalSlides = parseInt(slider.getAttribute('data-total')) || 1;

        // Hitung index baru secara sirkular
        currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
        
        // Update state Slider
        slider.setAttribute('data-current-index', currentIndex);
        slider.style.transform = `translateX(-${currentIndex * (100 / totalSlides)}%)`;

        // Update visual Dots dengan aman tanpa bentrok inline-style
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('w-4', 'bg-white', 'opacity-100');
                dot.classList.remove('w-2', 'bg-white/50', 'opacity-50');
            } else {
                dot.classList.remove('w-4', 'bg-white', 'opacity-100');
                dot.classList.add('w-2', 'bg-white/50', 'opacity-50');
            }
        });
    }
}

// Scope event listener khusus untuk elemen di dalam modal ini saja agar tidak bentrok antar-produk
document.getElementById('{{ $modalId }}').querySelectorAll('.radio-variasi').forEach(radio => {
    radio.addEventListener('change', function() {
        const sliderId = this.getAttribute('data-slider-id');
        const dotClass = this.getAttribute('data-dot-class');
        const indexFoto = parseInt(this.getAttribute('data-index-foto'));
        
        const slider = document.getElementById(sliderId);
        if (!slider) return;
        
        const totalSlides = parseInt(slider.getAttribute('data-total')) || 1;
        
        // 1. Pindah ke slide foto variasi
        slider.setAttribute('data-current-index', indexFoto);
        slider.style.transform = `translateX(-${indexFoto * (100 / totalSlides)}%)`;

        // 2. Update status aktif Dots
        const dots = document.querySelectorAll('.' + dotClass);
        dots.forEach((dot, index) => {
            if (index === indexFoto) {
                dot.classList.add('w-4', 'bg-white', 'opacity-100');
                dot.classList.remove('w-2', 'bg-white/50', 'opacity-50');
            } else {
                dot.classList.remove('w-4', 'bg-white', 'opacity-100');
                dot.classList.add('w-2', 'bg-white/50', 'opacity-50');
            }
        });
        
        // 3. Update Harga secara lokal di dalam form modal yang aktif saja
        const currentForm = this.closest('form');
        const displayHarga = currentForm.querySelector('.text-display-harga');
        const inputHargaHidden = currentForm.querySelector('.input-harga-hidden');
        const harga = this.getAttribute('data-harga');
        
        if (displayHarga && harga) {
            displayHarga.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        }
        if (inputHargaHidden && harga) {
            inputHargaHidden.value = harga;
        }
    });
});
</script>