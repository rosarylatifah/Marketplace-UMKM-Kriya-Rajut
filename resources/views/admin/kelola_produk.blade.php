@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
    <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Kelola Produk</h1>
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
        <p class="text-sm text-gray-400 mt-3">Daftar total produk yang tersedia.</p>
    </div>

    <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 relative">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, kategori, harga..."
                    class="text-[11px] font-medium tracking-wide border border-gray-200 rounded-full px-4 py-2.5 w-60 outline-none focus:border-[#001f3f] transition-all bg-white text-gray-700 placeholder-gray-300">
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="absolute right-3 top-3 text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="bg-[#F3F5F1] hover:bg-gray-200 border border-gray-200 text-[#001f3f] text-[10px] font-bold uppercase tracking-widest px-4 py-2.5 rounded-full transition-all">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <a href="{{ route('admin.produk.create') }}"
            class="inline-flex items-center gap-2 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-[11px] px-6 py-3 rounded-full transition-all duration-200 shadow-md hover:shadow-lg whitespace-nowrap">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Produk
        </a>

        <button type="button" data-modal-target="modal-kelola-kategori" data-modal-toggle="modal-kelola-kategori"
    class="inline-flex items-center gap-2 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.2em] text-[11px] px-6 py-3 rounded-full transition-all duration-200 whitespace-nowrap">
    <i class="fa-solid fa-tags text-xs"></i> Kelola Kategori
</button>
    </div>
</div>

{{-- Info Pencarian --}}
@if(request('search'))
    <div class="mb-4 text-xs text-gray-400 uppercase tracking-wider font-medium">
        Menampilkan hasil pencarian untuk: <span class="text-[#001f3f] font-bold">"{{ request('search') }}"</span>
    </div>
@endif

{{-- Alert Success --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('error') }}
    </div>
@endif

{{-- =====================================================================
     BULK DELETE: Toolbar (muncul saat ada produk yang dicentang)
     ===================================================================== --}}
<div id="bulk-toolbar"
    class="hidden mb-4 flex items-center justify-between gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3 transition-all">
    <p class="text-xs font-bold text-red-500 uppercase tracking-wider">
        <i class="fa-solid fa-circle-check mr-1"></i>
        <span id="bulk-count">0</span> produk dipilih
    </p>
    <div class="flex items-center gap-2">
        <button type="button" onclick="batalSeleksi()"
            class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-700 border border-gray-200 px-4 py-2 rounded-lg transition-all">
            Batal
        </button>
        <button type="button" onclick="konfirmasiBulkDelete()"
            class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-all">
            <i class="fa-solid fa-trash text-xs"></i> Hapus yang Dipilih
        </button>
    </div>
</div>

{{-- Form Bulk Delete (disembunyikan, disubmit via JS) --}}
<form id="bulk-delete-form" action="{{ route('admin.produk.bulkDestroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <div id="bulk-ids-container"></div>
</form>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    {{-- Checkbox Pilih Semua --}}
                    <th class="px-5 py-4 w-10">
                        <input type="checkbox" id="checkbox-all"
                            onchange="toggleSemuaCheckbox(this)"
                            class="w-4 h-4 rounded border-gray-300 accent-[#001f3f] cursor-pointer">
                    </th>
                    <th class="px-8 py-4">Produk</th>
                    <th class="px-8 py-4 text-center">Kategori</th>
                    <th class="px-8 py-4 text-center">Harga Dasar</th>
                    <th class="px-8 py-4 text-center">Rincian Variasi & Stok</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100" id="produk-tbody">
                @forelse ($produk as $p)
                <tr class="produk-row hover:bg-[#F3F5F1] transition-colors duration-150" data-id="{{ $p->id }}">

                    {{-- Checkbox per baris --}}
                    <td class="px-5 py-4">
                        <input type="checkbox"
                            class="produk-checkbox w-4 h-4 rounded border-gray-300 accent-[#001f3f] cursor-pointer"
                            value="{{ $p->id }}"
                            onchange="updateBulkToolbar()">
                    </td>

                    {{-- Nama Produk --}}
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#F3F5F1] border border-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                                <img src="{{ asset('images/' . $p->foto) }}" alt="{{ $p->nama }}"
                                    class="object-cover w-full h-full"
                                    onerror="this.src='https://placehold.co/100x100?text=Foto'">
                            </div>
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->nama }}</span>
                        </div>
                    </td>

                    {{-- Kategori --}}
                    <td class="px-8 py-4 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $p->kategori }}</span>
                    </td>

                    {{-- Harga --}}
                    <td class="px-8 py-4 text-center">
                        <div class="flex flex-col gap-1.5 justify-center items-center">
                            @if($p->variasis && $p->variasis->count() > 0)
                                @foreach($p->variasis as $v)
                                    <div class="flex items-center justify-center h-6">
                                        <span class="text-xs font-bold text-[#001f3f]">Rp {{ number_format($v->harga, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-xs font-bold text-[#001f3f]">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </td>

                    {{-- Rincian Variasi & Stok --}}
                    <td class="px-6 py-4">
                        <div class="w-full max-w-[260px] mx-auto">
                            @if($p->variasis && $p->variasis->count() > 0)
                                <table class="w-full text-[10px] uppercase font-medium">
                                    <tbody>
                                        @foreach($p->variasis as $v)
                                            <tr>
                                                <td class="py-1.5 pr-2 text-left text-gray-500 tracking-wider font-semibold align-middle break-words max-w-[170px]">
                                                    @if($v->ukuran && $v->warna)
                                                        {{ $v->ukuran }} - {{ $v->warna }}
                                                    @else
                                                        {{ $v->ukuran ?? $v->warna ?? 'Semua Varian' }}
                                                    @endif
                                                </td>
                                                <td class="py-1.5 text-right align-middle w-[70px]">
                                                    <span class="inline-block bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded font-bold whitespace-nowrap">
                                                        Stok: {{ $v->stok }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <span class="text-[10px] text-gray-300 italic text-center block py-2">Tidak ada variasi</span>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-8 py-4 text-center">
                        <div class="flex flex-col gap-1.5 justify-center items-center">
                            @if($p->variasis && $p->variasis->count() > 0)
                                @foreach($p->variasis as $v)
                                    <div class="flex items-center justify-center h-6">
                                        @if($v->stok > 0)
                                            <span class="inline-block bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full">Tersedia</span>
                                        @else
                                            <span class="inline-block bg-red-50 text-red-500 text-[9px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full">Habis</span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-center h-6">
                                    @if($p->stok > 0)
                                        <span class="inline-block bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full">Tersedia</span>
                                    @else
                                        <span class="inline-block bg-red-50 text-red-500 text-[9px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full">Habis</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-8 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.produk.edit', $p->id) }}"
                                class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-[#001f3f] hover:bg-[#F3F5F1] border border-gray-200 px-3 py-2 rounded-lg transition-all duration-150">
                                <i class="fa-solid fa-pen text-xs"></i> Edit
                            </a>
                            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin mau hapus produk {{ $p->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-lg transition-all duration-150">
                                    <i class="fa-solid fa-trash text-xs"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-magnifying-glass text-3xl text-gray-200"></i>
                            <p class="text-sm text-gray-400 uppercase tracking-widest font-semibold">Produk rajutan tidak ditemukan</p>
                            <p class="text-xs text-gray-300 -mt-1">Coba gunakan kata kunci atau filter lain</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL KELOLA KATEGORI --}}
<div id="modal-kelola-kategori" tabindex="-1" aria-hidden="true"
class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-start w-full md:inset-0 h-[calc(100%-1rem)] max-h-full pt-16"    <div class="relative p-4 w-full max-w-xl max-h-full">
<div class="relative bg-white rounded-xl shadow max-h-[80vh] overflow-y-auto">            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-[11px] font-bold text-[#001f3f] uppercase tracking-[0.2em]">Kelola Kategori</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-hide="modal-kelola-kategori">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="flex items-end gap-3 mb-6">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Nama Kategori Baru</label>
                        <input type="text" name="nama" required placeholder="Contoh: Sepatu Rajut"
                            class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-2.5 px-0 transition-all duration-300 bg-transparent outline-none">
                    </div>
                    <button type="submit"
                        class="bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-2.5 px-6 rounded-full uppercase text-[10px] tracking-[0.2em] transition-all duration-200 whitespace-nowrap">
                        + Tambah
                    </button>
                </form>
                @if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buka kembali modal kategori kalau ada error validasi
        const modal = document.getElementById('modal-kelola-kategori');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });
</script>
@endif

                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#F3F5F1] text-[9px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                                <th class="px-5 py-3">Nama</th>
                                <th class="px-5 py-3 text-center">Jml Produk</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
@forelse($kategoris as $k)
<tr id="row-{{ $k->id }}">
    <td class="px-5 py-3 text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $k->nama }}</td>
    <td class="px-5 py-3 text-center text-xs text-gray-500 font-semibold">{{ $k->jumlahProduk() }}</td>
    <td class="px-5 py-3 text-center">
        <div class="flex items-center justify-center gap-3">
            <button type="button" onclick="toggleEditKategori({{ $k->id }})" class="text-gray-400 hover:text-[#001f3f] transition-colors">
                <i class="fa-solid fa-pen text-xs"></i>
            </button>
            <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST"
                onsubmit="return confirm('Yakin mau hapus kategori {{ $k->nama }}? Produk yang sudah memakai kategori ini tidak akan terhapus.')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
<tr id="edit-row-{{ $k->id }}" class="hidden bg-gray-50/50">
    <td colspan="3" class="px-5 py-3">
        <form action="{{ route('admin.kategori.update', $k->id) }}" method="POST" class="flex items-center gap-2">
            @csrf @method('PUT')
            <input type="text" name="nama" value="{{ $k->nama }}" required
                class="flex-1 border border-gray-200 rounded p-2 text-xs font-bold uppercase tracking-widest focus:ring-[#001f3f] focus:border-[#001f3f] outline-none">
            <button type="submit" class="text-emerald-500 hover:text-emerald-600 px-2"><i class="fa-solid fa-check"></i></button>
            <button type="button" onclick="toggleEditKategori({{ $k->id }})" class="text-gray-400 hover:text-gray-600 px-2"><i class="fa-solid fa-xmark"></i></button>
        </form>
    </td>
</tr>
@empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-xs text-gray-400 uppercase tracking-widest">Belum ada kategori</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================================
     JAVASCRIPT: Logic Bulk Delete
     ===================================================================== --}}
<script>
    function getCheckboxesTerpilih() {
        return document.querySelectorAll('.produk-checkbox:checked');
    }
    
    function toggleEditKategori(id) {
    document.getElementById('edit-row-' + id).classList.toggle('hidden');
}

    function updateBulkToolbar() {
        const terpilih = getCheckboxesTerpilih();
        const jumlah = terpilih.length;
        const toolbar = document.getElementById('bulk-toolbar');
        const counter = document.getElementById('bulk-count');
        const checkboxAll = document.getElementById('checkbox-all');
        const totalCheckbox = document.querySelectorAll('.produk-checkbox').length;

        counter.textContent = jumlah;

        if (jumlah > 0) {
            toolbar.classList.remove('hidden');
            toolbar.classList.add('flex');
        } else {
            toolbar.classList.add('hidden');
            toolbar.classList.remove('flex');
        }

        // Update state checkbox "Pilih Semua"
        checkboxAll.indeterminate = jumlah > 0 && jumlah < totalCheckbox;
        checkboxAll.checked = jumlah === totalCheckbox && totalCheckbox > 0;
    }

    function toggleSemuaCheckbox(checkboxAll) {
        const semuaCheckbox = document.querySelectorAll('.produk-checkbox');
        semuaCheckbox.forEach(cb => cb.checked = checkboxAll.checked);
        updateBulkToolbar();
    }

    function batalSeleksi() {
        document.querySelectorAll('.produk-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('checkbox-all').checked = false;
        updateBulkToolbar();
    }

    function konfirmasiBulkDelete() {
        const terpilih = getCheckboxesTerpilih();
        const jumlah = terpilih.length;

        if (jumlah === 0) return;

        if (!confirm(`Yakin ingin menghapus ${jumlah} produk sekaligus? Tindakan ini tidak bisa dibatalkan.`)) return;

        // Isi form dengan ID yang terpilih lalu submit
        const container = document.getElementById('bulk-ids-container');
        container.innerHTML = '';
        terpilih.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('bulk-delete-form').submit();
    }
</script>

@endsection