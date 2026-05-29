@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Tambah Produk Baru</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Input detail produk rajutan terbaru beserta foto spesifik tiap variasi.</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-10 max-w-5xl mx-auto">

    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs">
            <p class="font-bold uppercase tracking-wider mb-2">Error! Tidak dapat memasukkan data.</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Detail Produk Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
        </div>

        {{-- ================= FORM MASUKAN VARIASI DINAMIS + FOTO PER VARIASI ================= --}}
        <div class="bg-gray-50/50 p-6 border border-gray-100 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 block">Variasi, Stok, Harga & Foto Warna</label>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Format: JPG/PNG. Maks 2MB. Foto pertama otomatis jadi thumbnail katalog.</p>
                </div>
                <button type="button" id="btn-tambah-variasi" 
                        class="bg-[#001f3f] text-white text-[9px] font-bold uppercase tracking-widest px-3 py-2 rounded-md hover:bg-gray-800 transition-all shadow-sm">
                    + Tambah Varian
                </button>
            </div>

            {{-- Tempat menampung baris variasi --}}
            <div id="wrapper-variasi" class="space-y-4">
                {{-- Baris Pertama (Default) --}}
                <div class="flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100/50 item-variasi">
                    <div class="w-full md:w-2/12">
                        <input type="text" name="variasi[0][ukuran]" placeholder="Ukuran (S, M, All Size)" required
                            class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="text" name="variasi[0][warna]" placeholder="Warna (Pink, Sage)" required
                            class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="number" name="variasi[0][stok]" min="0" value="0" required title="Stok" placeholder="Stok"
                            class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="number" name="variasi[0][harga]" min="0" placeholder="Harga (Rp)" required
                            class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    {{-- Input File Foto Varian --}}
                    <div class="w-full md:w-3/12">
                        <input type="file" name="variasi[0][foto]" required accept="image/*"
                            class="w-full text-[11px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>
                    <div class="w-auto flex justify-center">
                        <button type="button" class="text-red-400 hover:text-red-600 text-sm px-1 btn-hapus-variasi">
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

        <div class="flex gap-4 pt-4 border-t border-gray-100">
            <button type="submit"
                class="flex-1 bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-3.5 rounded-full uppercase text-[11px] tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                Simpan Produk
            </button>
            <a href="{{ route('admin.produk.index') }}"
                class="px-10 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 rounded-full font-bold uppercase text-[11px] tracking-[0.25em] transition-all duration-200 flex items-center justify-center">
                Batal
            </a>
        </div>
    </form>
</div>

{{-- ================= FULL LOGIKA JAVASCRIPT DINAMIS ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('wrapper-variasi');
        const btnTambah = document.getElementById('btn-tambah-variasi');
        let indexVariasi = 1;

        // Aksi tambah baris variasi baru
        btnTambah.addEventListener('click', function () {
            const barisBaru = document.createElement('div');
            barisBaru.className = "flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100/50 item-variasi";
            
            barisBaru.innerHTML = `
                <div class="w-full md:w-2/12">
                    <input type="text" name="variasi[${indexVariasi}][ukuran]" placeholder="Ukuran (S, M, All Size)" required
                        class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-2/12">
                    <input type="text" name="variasi[${indexVariasi}][warna]" placeholder="Warna (Pink, Sage)" required
                        class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-2/12">
                    <input type="number" name="variasi[${indexVariasi}][stok]" min="0" value="0" required title="Stok" placeholder="Stok"
                        class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-2/12">
                    <input type="number" name="variasi[${indexVariasi}][harga]" min="0" placeholder="Harga (Rp)" required
                        class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-3/12">
                    <input type="file" name="variasi[${indexVariasi}][foto]" required accept="image/*"
                        class="w-full text-[11px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </div>
                <div class="w-auto flex justify-center">
                    <button type="button" class="text-red-400 hover:text-red-600 text-sm px-1 btn-hapus-variasi">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(barisBaru);
            indexVariasi++;
        });

        // Aksi hapus baris variasi
        wrapper.addEventListener('click', function (e) {
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