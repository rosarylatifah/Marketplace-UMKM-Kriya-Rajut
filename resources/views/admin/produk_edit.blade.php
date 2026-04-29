@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-black uppercase tracking-tight">Edit Produk</h1>
    <p class="text-gray-500 text-sm">Ubah detail produk <strong>{{ $produk->nama }}</strong></p>
</div>

<div class="bg-white border border-black p-8 max-w-4xl">
    {{-- ACTION ke route update, METHOD pake PUT, dan jangan lupa enctype buat foto --}}
    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <label class="block text-xs font-bold text-black uppercase tracking-widest">Foto Produk (Kosongkan jika tidak diganti)</label>
            <div onclick="document.getElementById('input_foto').click()" class="border-2 border-dashed border-black w-full h-64 flex flex-col items-center justify-center bg-gray-50 group hover:bg-gray-100 transition-colors cursor-pointer relative overflow-hidden">
                {{-- Preview gambar yang udah ada --}}
                <img src="{{ asset('images/' . $produk->foto) }}" id="preview_img" class="absolute inset-0 object-cover w-full h-full opacity-40">
                
                <div class="relative z-10 flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-black mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-[10px] font-bold text-black uppercase bg-white/80 px-2 py-1" id="foto_label">Klik untuk Ganti Gambar</span>
                </div>
                <input type="file" name="foto" id="input_foto" class="hidden" onchange="updateLabel(this)">
            </div>
            <p class="text-[10px] text-gray-400">Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        <div class="space-y-5">
            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Nama Produk</label>
                <input type="text" name="nama" value="{{ $produk->nama }}" required 
                    class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold bg-white">
                        <option value="PAKAIAN" {{ $produk->kategori == 'PAKAIAN' ? 'selected' : '' }}>Pakaian</option>
                        <option value="AKSESORIS" {{ $produk->kategori == 'AKSESORIS' ? 'selected' : '' }}>Aksesoris</option>
                        <option value="DEKORASI" {{ $produk->kategori == 'DEKORASI' ? 'selected' : '' }}>Dekorasi</option>
                        <option value="AMIGURUMI" {{ $produk->kategori == 'AMIGURUMI' ? 'selected' : '' }}>Amigurumi</option>
                        <option value="TAS" {{ $produk->kategori == 'TAS' ? 'selected' : '' }}>Tas & Wadah</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Stok</label>
                    <input type="number" name="stok" value="{{ $produk->stok }}" required class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ $produk->harga }}" required class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold text-pink-600">
            </div>

            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm">{{ $produk->deskripsi }}</textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-3 border border-black rounded-none uppercase text-xs tracking-widest transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.produk.index') }}" class="px-6 bg-white hover:bg-gray-100 text-black border border-black rounded-none font-bold uppercase text-xs tracking-widest transition-colors flex items-center">
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
            
            // Biar bisa liat preview foto baru
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview_img').src = e.target.result;
                document.getElementById('preview_img').style.opacity = "1";
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection