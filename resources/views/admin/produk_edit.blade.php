@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Edit Produk</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">
        Ubah detail produk <span class="font-semibold text-[#001f3f]">{{ $produk->nama }}</span>.
        Kosongkan input foto jika tidak ingin menggantinya.
    </p>
</div>

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs max-w-5xl mx-auto">
        <p class="font-bold uppercase tracking-wider mb-2">Ada yang perlu diperbaiki:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl p-10 max-w-5xl mx-auto">
    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- ===== DETAIL PRODUK UTAMA ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Nama --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Nama Produk <span class="text-red-400">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium bg-transparent outline-none">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Kategori <span class="text-red-400">*</span>
                </label>
<select name="kategori" required
    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer bg-transparent outline-none">
    @foreach($kategoris as $k)
        <option value="{{ $k->kode }}" {{ old('kategori', $produk->kategori) == $k->kode ? 'selected' : '' }}>{{ $k->nama }}</option>
    @endforeach
</select>
            </div>

            {{-- Foto Display --}}
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Foto Display Utama
                    <span class="normal-case font-normal text-gray-300 ml-1">(kosongkan jika tidak ingin mengganti)</span>
                </label>
                <div class="flex items-center gap-4">
                    {{-- Preview foto lama --}}
                    @if($produk->foto)
                        <img src="{{ asset('images/' . $produk->foto) }}"
                            class="w-16 h-16 rounded-lg object-cover border border-gray-200 flex-shrink-0"
                            onerror="this.src='https://placehold.co/64x64?text=Foto'">
                    @endif
                    <input type="file" name="foto_display" accept="image/*"
                        class="w-full text-[11px] file:mr-2 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-gray-100 file:text-[#001f3f] cursor-pointer">
                </div>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 bg-gray-50/50 p-4 border border-gray-100 rounded-xl">
    <input type="checkbox" name="is_pilihan" id="is_pilihan" value="1"
        {{ old('is_pilihan', $produk->is_pilihan) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-gray-300 accent-[#001f3f] cursor-pointer">
    <label for="is_pilihan" class="text-[11px] font-bold uppercase tracking-widest text-gray-600 cursor-pointer">
        Tampilkan produk ini di "Koleksi Pilihan" Beranda
    </label>
</div>

        </div>

        {{-- ===== DESKRIPSI ===== --}}
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 bg-transparent outline-none resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        {{-- ===== VARIASI DINAMIS ===== --}}
        <div class="bg-gray-50/50 p-6 border border-gray-100 rounded-xl">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 block">
                        Variasi Produk <span class="text-red-400">*</span>
                    </label>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                        Ukuran/Warna opsional. Kosongkan file foto jika tidak ingin mengganti foto variasi.
                    </p>
                </div>
                <button type="button" id="btn-tambah-variasi"
                    class="bg-[#001f3f] text-white text-[9px] font-bold uppercase tracking-widest px-3 py-2 rounded-md hover:bg-gray-800 transition-all shadow-sm whitespace-nowrap">
                    + Tambah Varian
                </button>
            </div>

            {{-- Label kolom --}}
            <div class="hidden md:flex gap-3 mt-4 mb-1 px-1">
                <div class="w-2/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Ukuran</div>
                <div class="w-2/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Warna</div>
                <div class="w-1/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Stok <span class="text-red-400">*</span></div>
                <div class="w-2/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Harga <span class="text-red-400">*</span></div>
                <div class="w-4/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Foto <span class="italic font-normal">(opsional)</span></div>
                <div class="w-1/12"></div>
            </div>

            <div id="wrapper-variasi" class="space-y-3">
                @if($produk->variasis && $produk->variasis->count() > 0)
                    @foreach($produk->variasis as $index => $v)
                        <div class="flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100 item-variasi">
                            <input type="hidden" name="variasi[{{ $index }}][id]" value="{{ $v->id }}">

                            <div class="w-full md:w-2/12">
                                <input type="text" name="variasi[{{ $index }}][ukuran]"
                                    value="{{ old('variasi.'.$index.'.ukuran', $v->ukuran) }}" placeholder="S, M, L (opsional)"
                                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                            </div>
                            <div class="w-full md:w-2/12">
                                <input type="text" name="variasi[{{ $index }}][warna]"
                                    value="{{ old('variasi.'.$index.'.warna', $v->warna) }}" placeholder="Pink (opsional)"
                                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                            </div>
                            <div class="w-full md:w-1/12">
                                <input type="number" name="variasi[{{ $index }}][stok]"
                                    value="{{ old('variasi.'.$index.'.stok', $v->stok) }}" min="0" required placeholder="Stok"
                                    class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                            </div>
                            <div class="w-full md:w-2/12">
                                <input type="number" name="variasi[{{ $index }}][harga]"
                                    value="{{ old('variasi.'.$index.'.harga', $v->harga) }}" min="0" placeholder="Harga" required
                                    class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                            </div>
                            <div class="w-full md:w-4/12 flex items-center gap-2">
                                @if($v->foto)
                                    <img src="{{ asset('images/' . $v->foto) }}"
                                        class="w-8 h-8 rounded object-cover border border-gray-200 flex-shrink-0"
                                        onerror="this.src='https://placehold.co/32x32?text=?'">
                                @else
                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center flex-shrink-0 border border-gray-200">
                                        <i class="fa-regular fa-image text-xs text-gray-300"></i>
                                    </div>
                                @endif
                                <input type="file" name="variasi[{{ $index }}][foto]" accept="image/*"
                                    class="w-full text-[10px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                            </div>
                            <div class="w-auto md:w-1/12 flex justify-center">
                                <button type="button" class="text-gray-300 hover:text-red-500 text-sm px-1 btn-hapus-variasi">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback kalau tidak ada variasi --}}
                    <div class="flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100 item-variasi">
                        <div class="w-full md:w-2/12">
                            <input type="text" name="variasi[0][ukuran]" placeholder="Ukuran (opsional)"
                                class="w-full border border-gray-200 rounded p-2 text-xs bg-white outline-none">
                        </div>
                        <div class="w-full md:w-2/12">
                            <input type="text" name="variasi[0][warna]" placeholder="Warna (opsional)"
                                class="w-full border border-gray-200 rounded p-2 text-xs bg-white outline-none">
                        </div>
                        <div class="w-full md:w-1/12">
                            <input type="number" name="variasi[0][stok]" min="0" value="0" required placeholder="Stok"
                                class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold bg-white outline-none">
                        </div>
                        <div class="w-full md:w-2/12">
                            <input type="number" name="variasi[0][harga]" min="0" value="{{ $produk->harga }}" required placeholder="Harga"
                                class="w-full border border-gray-200 rounded p-2 text-xs font-bold bg-white outline-none">
                        </div>
                        <div class="w-full md:w-4/12">
                            <input type="file" name="variasi[0][foto]" accept="image/*"
                                class="w-full text-[10px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-semibold file:bg-gray-100 file:text-gray-700 cursor-pointer">
                        </div>
                        <div class="w-auto md:w-1/12 flex justify-center">
                            <button type="button" class="text-gray-300 hover:text-red-500 text-sm px-1 btn-hapus-variasi">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-4 pt-4 border-t border-gray-100">
            <button type="submit"
                class="flex-1 bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-3.5 rounded-full uppercase text-[11px] tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.produk.index') }}"
                class="px-10 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 rounded-full font-bold uppercase text-[11px] tracking-[0.25em] transition-all duration-200 flex items-center justify-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper   = document.getElementById('wrapper-variasi');
    const btnTambah = document.getElementById('btn-tambah-variasi');
    let indexVariasi = Date.now();

    btnTambah.addEventListener('click', function () {
        const barisBaru = document.createElement('div');
        barisBaru.className = 'flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100 item-variasi';

        barisBaru.innerHTML = `
            <div class="w-full md:w-2/12">
                <input type="text" name="variasi[${indexVariasi}][ukuran]" placeholder="Ukuran (opsional)"
                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-2/12">
                <input type="text" name="variasi[${indexVariasi}][warna]" placeholder="Warna (opsional)"
                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-1/12">
                <input type="number" name="variasi[${indexVariasi}][stok]" min="0" value="0" required placeholder="Stok"
                    class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-2/12">
                <input type="number" name="variasi[${indexVariasi}][harga]" min="0" placeholder="Harga" required
                    class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-4/12 flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center flex-shrink-0 border border-gray-200">
                    <i class="fa-regular fa-image text-xs text-gray-300"></i>
                </div>
                <input type="file" name="variasi[${indexVariasi}][foto]" accept="image/*"
                    class="w-full text-[10px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>
            <div class="w-auto md:w-1/12 flex justify-center">
                <button type="button" class="text-gray-300 hover:text-red-500 text-sm px-1 btn-hapus-variasi">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        `;

        wrapper.appendChild(barisBaru);
        indexVariasi++;
    });

    wrapper.addEventListener('click', function (e) {
        const tombol = e.target.closest('.btn-hapus-variasi');
        if (!tombol) return;
        const semuaBaris = wrapper.querySelectorAll('.item-variasi');
        if (semuaBaris.length > 1) {
            tombol.closest('.item-variasi').remove();
        } else {
            alert('Minimal harus menyisakan 1 variasi produk!');
        }
    });
});
</script>

@endsection