@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-black uppercase tracking-tight">Tambah Produk Baru</h1>
    <p class="text-gray-500 text-sm">Input detail produk rajutan terbaru ke katalog Namonic</p>
</div>

<div class="bg-white border border-black p-8 max-w-4xl">
    {{-- ACTION diarahkan ke route store --}}
    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @csrf
        <div class="space-y-4">
            <label class="block text-xs font-bold text-black uppercase tracking-widest">Foto Produk</label>
            {{-- Tambahin onclick biar pas kotak diklik, input filenya kebuka --}}
            <div onclick="document.getElementById('input_foto').click()" class="border-2 border-dashed border-black w-full h-64 flex flex-col items-center justify-center bg-gray-50 group hover:bg-gray-100 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-[10px] font-bold text-gray-500 uppercase" id="foto_label">Klik untuk Upload Gambar</span>
                {{-- NAME WAJIB ADA: 'foto' --}}
                <input type="file" name="foto" id="input_foto" class="hidden" onchange="updateLabel(this)">
            </div>
            <p class="text-[10px] text-gray-400">Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        <div class="space-y-5">
            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Nama Produk</label>
                {{-- Kelas 'uppercase' gue apus, biar sesuai inputan lu --}}
                <input type="text" name="nama" required 
                    class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-semibold" 
                    placeholder="Contoh: Tas Rajut Sakura">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Kategori</label>
                    {{-- NAME WAJIB ADA: 'kategori' --}}
                    <select name="kategori" class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold bg-white">
                        <option value="TAS">Pakaian</option>
                        <option value="AKSESORIS">Aksesoris</option>
                        <option value="PAKAIAN">Dekorasi</option>
                        <option value="AMIGURUMI">Amigurumi</option>
                        <option value="AMIGURUMI">Tas & Wadah</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Stok</label>
                    {{-- NAME WAJIB ADA: 'stok' --}}
                    <input type="number" name="stok" required class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold" placeholder="0">
                </div>
            </div>

            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Harga (Rp)</label>
                {{-- NAME WAJIB ADA: 'harga' --}}
                <input type="number" name="harga" required class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm font-bold text-pink-600" placeholder="0">
            </div>

            <div>
                <label class="block mb-1 text-xs font-bold text-black uppercase tracking-widest">Deskripsi Singkat</label>
                {{-- NAME WAJIB ADA: 'deskripsi' --}}
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-black rounded-none focus:ring-0 focus:border-pink-600 outline-none text-sm" placeholder="Ceritakan detail rajutanmu..."></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 border border-black rounded-none uppercase text-xs tracking-widest transition-colors">
                    Simpan Produk
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
        }
    }
</script>
@endsection