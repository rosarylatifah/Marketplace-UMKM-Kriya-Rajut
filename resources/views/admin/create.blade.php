@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Tambah Produk Baru</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Input detail produk rajutan terbaru ke katalog.</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-10 max-w-4xl">

    {{-- TARUH DI SINI (DI ATAS FORM) --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs">
            <p class="font-bold uppercase tracking-wider mb-2">Waduh Zar, ada error di inputan lu nih:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @csrf

        {{-- Upload Foto --}}
        <div class="space-y-3">
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400">Foto Produk (Bisa Pilih Banyak)</label>
            <div onclick="document.getElementById('input_foto').click()"
                class="border-2 border-dashed border-gray-200 w-full h-64 flex flex-col items-center justify-center bg-[#F3F5F1] hover:border-[#001f3f] hover:bg-white transition-all duration-150 cursor-pointer rounded-xl">
                <i class="fa-regular fa-image text-3xl text-gray-300 mb-3"></i>
                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400 text-center px-4" id="foto_label">Klik untuk Upload Gambar</span>
                
                {{-- PERUBAHAN UTAMA: name="foto[]" berupa array dan ada atribut multiple --}}
                <input type="file" name="foto[]" id="input_foto" class="hidden" multiple onchange="updateLabel(this)" required>
            </div>
            
            @error('foto')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('foto.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            
            <p class="text-[10px] text-gray-300 uppercase tracking-widest">Format: JPG, PNG. Maksimal 2MB per file.</p>
        </div>

        {{-- Detail Produk --}}
        <div class="space-y-6">

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Nama Produk</label>
                <input type="text" name="nama" required placeholder="Contoh: Tas Rajut Sakura"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300 bg-transparent outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Kategori</label>
                <select name="kategori"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer bg-transparent outline-none">
                    <option value="PAKAIAN">Pakaian</option>
                    <option value="AKSESORIS">Aksesoris</option>
                    <option value="DEKORASI">Dekorasi</option>
                    <option value="AMIGURUMI">Amigurumi</option>
                    <option value="TAS">Tas & Wadah</option>
                </select>
            </div>

            {{-- ================= FORM MASUKAN VARIASI DINAMIS + HARGA (KODE PRO) ================= --}}
            <div class="mb-4 bg-gray-50/50 p-4 border border-gray-100 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400">Variasi Produk, Stok, & Harga</label>
                    <button type="button" id="btn-tambah-variasi" 
                            class="bg-[#001f3f] text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1.5 rounded-md hover:bg-gray-800 transition-all shadow-sm">
                        + Tambah Varian
                    </button>
                </div>

                {{-- Tempat menampung baris variasi --}}
                <div id="wrapper-variasi" class="space-y-3">
                    <div class="flex flex-wrap md:flex-nowrap gap-2 items-center pb-2 item-variasi">
                        <div class="w-full md:w-4/12">
                            <input type="text" name="variasi[0][ukuran]" placeholder="Ukuran (S, M, All Size)" required
                                class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                        </div>
                        <div class="w-full md:w-3/12">
                            <input type="text" name="variasi[0][warna]" placeholder="Warna (Pink, Sage)" required
                                class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                        </div>
                        <div class="w-full md:w-2/12">
                            <input type="number" name="variasi[0][stok]" min="0" value="0" required title="Stok" placeholder="Stok"
                                class="w-full border border-gray-200 rounded p-1.5 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                        </div>
                        <div class="w-full md:w-3/12">
                            <input type="number" name="variasi[0][harga]" min="0" placeholder="Harga (Rp)" required
                                class="w-full border border-gray-200 rounded p-1.5 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                        </div>
                        <div class="w-auto">
                            <button type="button" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase px-1 btn-hapus-variasi">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Ceritakan detail rajutanmu..."
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 placeholder-gray-300 bg-transparent outline-none resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-3.5 rounded-full uppercase text-[11px] tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.produk.index') }}"
                    class="px-8 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 rounded-full font-bold uppercase text-[11px] tracking-[0.25em] transition-all duration-200 flex items-center">
                    Batal
                </a>
            </div>

        </div>
    </form>
</div>

{{-- ================= FULL LOGIKA JAVASCRIPT GABUNGAN ================= --}}
<script>
    // Fungsi bawaan temen lu untuk update label nama foto
    function updateLabel(input) {
        const label = document.getElementById('foto_label');
        if (input.files && input.files.length > 0) {
            if (input.files.length === 1) {
                label.innerText = input.files[0].name;
            } else {
                label.innerText = input.files.length + " Foto Terpilih";
            }
        } else {
            label.innerText = "Klik untuk Upload Gambar";
        }
    }

    // Fungsi dinamisasi variasi pro saat DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('wrapper-variasi');
        const btnTambah = document.getElementById('btn-tambah-variasi');
        let indexVariasi = 1;

        // Aksi tambah baris variasi
        btnTambah.addEventListener('click', function () {
            const barisBaru = document.createElement('div');
            barisBaru.className = "flex flex-wrap md:flex-nowrap gap-2 items-center pb-2 item-variasi";
            // Ganti isi innerHTML di dalam script tambah variasi lu jadi gini:
            barisBaru.innerHTML = `
                <div class="w-full md:w-4/12">
                    <input type="text" name="variasi[${indexVariasi}][ukuran]" placeholder="Ukuran (S, M, All Size)" required
                        class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-3/12">
                    <input type="text" name="variasi[${indexVariasi}][warna]" placeholder="Warna (Pink, Sage)" required
                        class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-2/12">
                    <input type="number" name="variasi[${indexVariasi}][stok]" min="0" value="0" required title="Stok" placeholder="Stok"
                        class="w-full border border-gray-200 rounded p-1.5 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-3/12">
                    <input type="number" name="variasi[${indexVariasi}][harga]" min="0" placeholder="Harga (Rp)" required
                        class="w-full border border-gray-200 rounded p-1.5 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-auto">
                    <button type="button" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase px-1 btn-hapus-variasi">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(barisBaru);
            indexVariasi++;
        });

        // Aksi hapus baris variasi
        wrapper.addEventListener('click', function (e) {
            // Cek apakah yang diklik tombol hapus atau icon tong sampah di dalamnya
            if (e.target.classList.contains('btn-hapus-variasi') || e.target.closest('.btn-hapus-variasi')) {
                const semuaBaris = wrapper.querySelectorAll('.item-variasi');
                if (semuaBaris.length > 1) {
                    const barisTarget = e.target.closest('.item-variasi');
                    barisTarget.remove();
                } else {
                    alert('Minimal harus ada 1 variasi produk ya, Zar!');
                }
            }
        });
    });
</script>

@endsection