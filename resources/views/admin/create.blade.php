@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Tambah Produk Baru</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Input detail produk rajutan. Foto per variasi <span class="font-semibold text-[#001f3f]">tidak wajib</span> — cukup isi ukuran atau warna saja.</p>
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

        {{-- ===== DETAIL PRODUK UTAMA ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Nama Produk --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Nama Produk <span class="text-red-400">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    placeholder="Contoh: Tas Rajut Sakura"
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300 bg-transparent outline-none">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Kategori <span class="text-red-400">*</span>
                </label>
<select name="kategori" required
    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer bg-transparent outline-none">
    @foreach($kategoris as $k)
        <option value="{{ $k->kode }}" {{ old('kategori') == $k->kode ? 'selected' : '' }}>{{ $k->nama }}</option>
    @endforeach
</select>
            </div>

            {{-- Foto Display Utama --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                    Foto Display Utama <span class="text-red-400">*</span>
                </label>
                <input type="file" name="foto_display" required accept="image/*"
                    class="w-full text-[11px] file:mr-2 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-gray-100 file:text-[#001f3f] cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-1">JPG, JPEG, PNG. Maks 2MB.</p>
            </div>
            <div class="md:col-span-2 flex items-center gap-3 bg-gray-50/50 p-4 border border-gray-100 rounded-xl">
    <input type="checkbox" name="is_pilihan" id="is_pilihan" value="1"
        {{ old('is_pilihan') ? 'checked' : '' }}
        class="w-4 h-4 rounded border-gray-300 accent-[#001f3f] cursor-pointer">
    <label for="is_pilihan" class="text-[11px] font-bold uppercase tracking-widest text-gray-600 cursor-pointer">
        Tampilkan produk ini di "Koleksi Pilihan" Beranda
    </label>
</div>

        </div>

        {{-- ===== DESKRIPSI ===== --}}
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">
                Deskripsi <span class="text-red-400">*</span>
            </label>
            <textarea name="deskripsi" rows="3" required placeholder="Ceritakan detail rajutanmu..."
                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 placeholder-gray-300 bg-transparent outline-none resize-none">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- ===== VARIASI DINAMIS ===== --}}
        <div class="bg-gray-50/50 p-6 border border-gray-100 rounded-xl">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 block">
                        Variasi Produk <span class="text-red-400">*</span>
                    </label>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                        Isi ukuran <span class="font-semibold">atau</span> warna saja — tidak wajib keduanya. Foto per variasi juga opsional.
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
                <div class="w-2/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Stok <span class="text-red-400">*</span></div>
                <div class="w-2/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Harga (Rp) <span class="text-red-400">*</span></div>
                <div class="w-3/12 text-[9px] uppercase tracking-widest text-gray-400 font-bold">Foto Variasi <span class="italic font-normal">(opsional)</span></div>
                <div class="w-1/12"></div>
            </div>

            {{-- Container baris variasi --}}
            <div id="wrapper-variasi" class="space-y-3">
                {{-- Baris Pertama (Default) --}}
                <div class="flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100 item-variasi">
                    <div class="w-full md:w-2/12">
                        <input type="text" name="variasi[0][ukuran]" placeholder="S, M, L (opsional)"
                            class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="text" name="variasi[0][warna]" placeholder="Pink, Putih (opsional)"
                            class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="number" name="variasi[0][stok]" min="0" value="0" required placeholder="Stok"
                            class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-2/12">
                        <input type="number" name="variasi[0][harga]" min="0" placeholder="Harga" required
                            class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
                    </div>
                    <div class="w-full md:w-3/12">
                        <input type="file" name="variasi[0][foto]" accept="image/*"
                            class="w-full text-[10px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>
                    <div class="w-auto md:w-1/12 flex justify-center">
                        <button type="button" class="text-gray-300 hover:text-red-500 text-sm px-1 btn-hapus-variasi" title="Hapus baris ini">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== GALERI FOTO (OPSIONAL) ===== --}}
        <div class="bg-gray-50/50 p-6 border border-gray-100 rounded-xl">
            <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
                Galeri Foto Tambahan
                <span class="normal-case font-normal text-gray-300 ml-1">(opsional)</span>
            </label>
            <p class="text-[10px] text-gray-400 mb-4 uppercase tracking-wider">
                Foto pendukung untuk slider/carousel di halaman detail produk pembeli.
            </p>

            <div class="border-2 border-dashed border-gray-200 hover:border-[#001f3f] bg-white rounded-xl p-6 text-center transition-all duration-300 relative">
                <input type="file" name="foto_galeri[]" multiple accept="image/*" id="input-galeri-foto"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="space-y-2 pointer-events-none">
                    <div class="text-gray-300 text-2xl"><i class="fa-regular fa-images"></i></div>
                    <p class="text-xs font-semibold text-gray-400" id="text-galeri-status">Klik atau seret foto ke sini</p>
                    <p class="text-[10px] text-gray-300">JPG, JPEG, PNG — Maks. 2MB per foto</p>
                </div>
            </div>
            <div id="preview-nama-file" class="mt-3 flex flex-wrap gap-2"></div>
        </div>

        {{-- Tombol Aksi --}}
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper   = document.getElementById('wrapper-variasi');
    const btnTambah = document.getElementById('btn-tambah-variasi');
    let indexVariasi = 1;

    // ===== TAMBAH BARIS VARIASI =====
    btnTambah.addEventListener('click', function () {
        const barisBaru = document.createElement('div');
        barisBaru.className = 'flex flex-wrap md:flex-nowrap gap-3 items-center pb-3 border-b border-gray-100 item-variasi';

        barisBaru.innerHTML = `
            <div class="w-full md:w-2/12">
                <input type="text" name="variasi[${indexVariasi}][ukuran]" placeholder="S, M, L (opsional)"
                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-2/12">
                <input type="text" name="variasi[${indexVariasi}][warna]" placeholder="Pink, Putih (opsional)"
                    class="w-full border border-gray-200 rounded p-2 text-xs focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-2/12">
                <input type="number" name="variasi[${indexVariasi}][stok]" min="0" value="0" required placeholder="Stok"
                    class="w-full border border-gray-200 rounded p-2 text-xs text-center font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-2/12">
                <input type="number" name="variasi[${indexVariasi}][harga]" min="0" placeholder="Harga" required
                    class="w-full border border-gray-200 rounded p-2 text-xs font-bold focus:ring-[#001f3f] focus:border-[#001f3f] bg-white outline-none">
            </div>
            <div class="w-full md:w-3/12">
                <input type="file" name="variasi[${indexVariasi}][foto]" accept="image/*"
                    class="w-full text-[10px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>
            <div class="w-auto md:w-1/12 flex justify-center">
                <button type="button" class="text-gray-300 hover:text-red-500 text-sm px-1 btn-hapus-variasi" title="Hapus baris ini">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(barisBaru);
        indexVariasi++;
    });

    // ===== HAPUS BARIS VARIASI =====
    wrapper.addEventListener('click', function (e) {
        const tombol = e.target.closest('.btn-hapus-variasi');
        if (!tombol) return;
        const semuaBaris = wrapper.querySelectorAll('.item-variasi');
        if (semuaBaris.length > 1) {
            tombol.closest('.item-variasi').remove();
        } else {
            alert('Minimal harus ada 1 variasi produk!');
        }
    });

    // ===== GALERI FOTO PREVIEW =====
    const inputGaleri  = document.getElementById('input-galeri-foto');
    const textStatus   = document.getElementById('text-galeri-status');
    const previewNama  = document.getElementById('preview-nama-file');
    let kumpulanFileGaleri = [];

    inputGaleri.addEventListener('change', function () {
        kumpulanFileGaleri = [...kumpulanFileGaleri, ...Array.from(this.files)];
        perbaruiGaleri();
    });

    function perbaruiGaleri() {
        previewNama.innerHTML = '';
        const dt = new DataTransfer();

        if (kumpulanFileGaleri.length > 0) {
            textStatus.textContent = `${kumpulanFileGaleri.length} foto dipilih`;
            textStatus.className = 'text-xs font-semibold text-green-600';

            kumpulanFileGaleri.forEach((file, i) => {
                dt.items.add(file);
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-[#001f3f] border border-blue-100 text-[10px] font-medium rounded-md';
                badge.textContent = file.name;

                const x = document.createElement('button');
                x.type = 'button';
                x.className = 'text-red-400 hover:text-red-600 font-bold ml-1 text-[11px]';
                x.innerHTML = '&times;';
                x.addEventListener('click', () => {
                    kumpulanFileGaleri.splice(i, 1);
                    perbaruiGaleri();
                });
                badge.appendChild(x);
                previewNama.appendChild(badge);
            });
        } else {
            textStatus.textContent = 'Klik atau seret foto ke sini';
            textStatus.className = 'text-xs font-semibold text-gray-400';
        }

        inputGaleri.files = dt.files;
    }
});
</script>

@endsection