@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
    <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Kelola Produk</h1>
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    </div>

    {{-- 🔥 BARU: Form Search Terintegrasi + Tombol Tambah Produk --}}
    <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2 relative">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama, kategori, harga..." 
                    class="text-[11px] font-medium tracking-wide border border-gray-200 rounded-full px-4 py-2.5 w-60 outline-none focus:border-[#001f3f] transition-all bg-white text-gray-700 placeholder-gray-300">
                
                {{-- Tombol Reset Silang (muncul cuma pas ada input pencarian) --}}
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
    </div>
</div>

{{-- Info Keterangan Keyword Pencarian --}}
@if(request('search'))
    <div class="mb-4 text-xs text-gray-400 uppercase tracking-wider font-medium">
        Menampilkan hasil pencarian untuk: <span class="text-[#001f3f] font-bold">"{{ request('search') }}"</span>
    </div>
@endif

{{-- Alert Success Bawaan --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">Produk</th>
                    <th class="px-8 py-4 text-center">Kategori</th>
                    <th class="px-8 py-4 text-center">Harga Dasar</th>
                    <th class="px-8 py-4 text-center">Rincian Variasi & Stok</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                {{-- Modifikasi Menggunakan Forelse Biar Sempurna Pas Gak Nemu Barang --}}
                @forelse ($produk as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    {{-- Nama Produk --}}
                    <td class="px-8 py-4 vertical-align-middle">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#F3F5F1] border border-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                                <img src="{{ asset('images/' . $p->foto) }}" alt="{{ $p->nama }}" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/100x100?text=Foto'">
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

                            <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus produk {{ $p->nama }}?')">
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
                {{-- Tampilan kalau produk kosong / keyword pencarian gak dapet --}}
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
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

@endsection