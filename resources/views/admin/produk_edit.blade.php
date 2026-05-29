@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Edit Produk</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Ubah detail produk <span class="font-semibold text-[#001f3f]">{{ $produk->nama }}</span></p>
</div>

{{-- Radar Error Validasi --}}
@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs max-w-4xl">
        <p class="font-bold uppercase tracking-wider mb-2">Waduh Zar, ada yang salah pas ngedit nih:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl p-10 max-w-4xl">
    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @csrf
        @method('PUT')

        {{-- Upload Foto --}}
        <div class="space-y-3">
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400">Foto Produk <span class="text-gray-300">(Kosongkan jika tidak diganti)</span></label>
            <div onclick="document.getElementById('input_foto').click()"
                class="border-2 border-dashed border-gray-200 w-full h-64 flex flex-col items-center justify-center hover:border-[#001f3f] hover:bg-[#F3F5F1] transition-all duration-150 cursor-pointer rounded-xl relative overflow-hidden">
                <img src="{{ asset('images/' . $produk->foto) }}" id="preview_img" class="absolute inset-0 object-cover w-full h-full opacity-40 rounded-xl">
                <div class="relative z-10 flex flex-col items-center">
                    <i class="fa-regular fa-image text-3xl text-gray-300 mb-3"></i>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400 bg-white/80 px-3 py-1 rounded-full" id="foto_label">Klik untuk Ganti Gambar</span>
                </div>
                <input type="file" name="foto" id="input_foto" class="hidden" onchange="updateLabel(this)">
            </div>
            <p class="text-[10px] text-gray-300 uppercase tracking-widest">Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        {{-- Detail Produk --}}
        <div class="space-y-6">

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Nama Produk</label>
                <input type="text" name="nama" value="{{ $produk->nama }}" required
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium bg-transparent outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Kategori</label>
                <select name="kategori"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer bg-transparent outline-none">
                    <option value="PAKAIAN" {{ $produk->kategori == 'PAKAIAN' ? 'selected' : '' }}>Pakaian</option>
                    <option value="AKSESORIS" {{ $produk->kategori == 'AKSESORIS' ? 'selected' : '' }}>Aksesoris</option>
                    <option value="DEKORASI" {{ $produk->kategori == 'DEKORASI' ? 'selected' : '' }}>Dekorasi</option>
                    <option value="AMIGURUMI" {{ $produk->kategori == 'AMIGURUMI' ? 'selected' : '' }}>Amigurumi</option>
                    <option value="TAS" {{ $produk->kategori == 'TAS' ? 'selected' : '' }}>Tas & Wadah</option>
                </select>
            </div>

            {{-- ================= KODE EDIT VARIASI DINAMIS (HARGA & STOK IKUT PECAH) ================= --}}
            <div class="bg-gray-50/50 p-4 border border-gray-100 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400">Variasi Produk, Stok, & Harga</label>
                    <button type="button" id="btn-tambah-variasi" 
                            class="bg-[#001f3f] text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1.5 rounded-md hover:bg-gray-800 transition-all shadow-sm">
                        + Tambah Varian
                    </button>
                </div>

                {{-- Wrapper Container Variasi --}}
                <div id="wrapper-variasi" class="space-y-3">
                    @if($produk->variasis && $produk->variasis->count() > 0)
                        @foreach($produk->variasis as $index => $v)
                            <div class="flex flex-wrap md:flex-nowrap gap-2 items-center pb-2 item-variasi">
                                <div class="w-full md:w-4/12">
                                    <input type="text" name="variasi[{{ $index }}][ukuran]" value="{{ $v->ukuran }}" placeholder="Ukuran" required
                                           class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                                </div>
                                <div class="w-full md:w-3/12">
                                    <input type="text" name="variasi[{{ $index }}][warna]" value="{{ $v->warna }}" placeholder="Warna" required
                                           class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                                </div>
                                <div class="w-full md:w-2/12">
                                    <input type="number" name="variasi[{{ $index }}][stok]" value="{{ $v->stok }}" min="0" required title="Stok" placeholder="Stok"
                                           class="w-full border border-gray-200 rounded p-1.5 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                                </div>
                                <div class="w-full md:w-3/12">
                                    <input type="number" name="variasi[{{ $index }}][harga]" value="{{ $v->harga }}" min="0" placeholder="Harga" required
                                           class="w-full border border-gray-200 rounded p-1.5 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                                </div>
                                <div class="w-auto">
                                    <button type="button" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase px-1 btn-hapus-variasi">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex flex-wrap md:flex-nowrap gap-2 items-center pb-2 item-variasi">
                            <div class="w-full md:w-4/12">
                                <input type="text" name="variasi[0][ukuran]" placeholder="Ukuran (S, M)" required
                                       class="w-full border border-gray-200 rounded p-1.5 text-xs bg-white outline-none">
                            </div>
                            <div class="w-full md:w-3/12">
                                <input type="text" name="variasi[0][warna]" placeholder="Warna" required
                                       class="w-full border border-gray-200 rounded p-1.5 text-xs bg-white outline-none">
                            </div>
                            <div class="w-full md:w-2/12">
                                <input type="number" name="variasi[0][stok]" min="0" value="0" required placeholder="Stok"
                                       class="w-full border border-gray-200 rounded p-1.5 text-xs text-center font-bold bg-white outline-none">
                            </div>
                            <div class="w-full md:w-3/12">
                                <input type="number" name="variasi[0][harga]" min="0" value="{{ $produk->harga }}" placeholder="Harga" required
                                       class="w-full border border-gray-200 rounded p-1.5 text-xs font-bold bg-white outline-none">
                            </div>
                            <div class="w-auto">
                                <button type="button" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase px-1 btn-hapus-variasi">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 bg-transparent outline-none resize-none">{{ $produk->deskripsi }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-3.5 rounded-full uppercase text-[11px] tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.produk.index') }}"
                    class="px-8 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 rounded-full font-bold uppercase text-[11px] tracking-[0.25em] transition-all duration-200 flex items-center">
                    Batal
                </a>
            </div>

        </div>
    </form>
</div>

{{-- SCRIPT JAVASCRIPT VARIASI DINAMIS UNTUK HALAMAN EDIT --}}
<script>
    function updateLabel(input) {
        if (input.files && input.files[0]) {
            document.getElementById('foto_label').innerText = input.files[0].name;
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview_img').src = e.target.result;
                document.getElementById('preview_img').style.opacity = "1";
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('wrapper-variasi');
        const btnTambah = document.getElementById('btn-tambah-variasi');
        
        // Hitung index awal dari jumlah variasi lama yang sudah ter-load
        let indexVariasi = document.querySelectorAll('.item-variasi').length;

        // Fungsi Tambah Baris Varian Baru
        btnTambah.addEventListener('click', function () {
            const barisBaru = document.createElement('div');
            barisBaru.className = 'flex flex-wrap md:flex-nowrap gap-2 items-center pb-2 item-variasi';
            
            barisBaru.innerHTML = `
                <div class="w-full md:w-4/12">
                    <input type="text" name="variasi[${indexVariasi}][ukuran]" placeholder="Ukuran" required
                           class="w-full border border-gray-200 rounded p-1.5 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                </div>
                <div class="w-full md:w-3/12">
                    <input type="text" name="variasi[${indexVariasi}][warna]" placeholder="Warna" required
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

        // Fungsi Hapus Baris Varian (Delegation Event)
        wrapper.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus-variasi')) {
                const baris = e.target.closest('.item-variasi');
                // Kasih validasi minimal harus ada sisa 1 baris di form
                if (document.querySelectorAll('.item-variasi').length > 1) {
                    baris.remove();
                } else {
                    alert('Zar, minimal harus menyisakan 1 variasi produk ya!');
                }
            }
        });
    });
</script>

@endsection