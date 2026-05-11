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
    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @csrf

        {{-- Upload Foto --}}
        <div class="space-y-3">
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400">Foto Produk</label>
            <div onclick="document.getElementById('input_foto').click()"
                class="border-2 border-dashed border-gray-200 w-full h-64 flex flex-col items-center justify-center bg-[#F3F5F1] hover:border-[#001f3f] hover:bg-white transition-all duration-150 cursor-pointer rounded-xl">
                <i class="fa-regular fa-image text-3xl text-gray-300 mb-3"></i>
                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400" id="foto_label">Klik untuk Upload Gambar</span>
                <input type="file" name="foto" id="input_foto" class="hidden" onchange="updateLabel(this)">
            </div>
            <p class="text-[10px] text-gray-300 uppercase tracking-widest">Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        {{-- Detail Produk --}}
        <div class="space-y-6">

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Nama Produk</label>
                <input type="text" name="nama" required placeholder="Contoh: Tas Rajut Sakura"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300 bg-transparent outline-none">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Kategori</label>
                    <select name="kategori"
                        class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer bg-transparent outline-none">
                        <option value="TAS">Pakaian</option>
                        <option value="AKSESORIS">Aksesoris</option>
                        <option value="PAKAIAN">Dekorasi</option>
                        <option value="AMIGURUMI">Amigurumi</option>
                        <option value="AMIGURUMI">Tas & Wadah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Stok</label>
                    <input type="number" name="stok" required placeholder="0"
                        class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-bold placeholder-gray-300 bg-transparent outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Harga (Rp)</label>
                <input type="number" name="harga" required placeholder="0"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-bold placeholder-gray-300 bg-transparent outline-none">
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

<script>
    function updateLabel(input) {
        if (input.files && input.files[0]) {
            document.getElementById('foto_label').innerText = input.files[0].name;
        }
    }
</script>

@endsection