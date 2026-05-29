@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-10">
    <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Manajemen</p>
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Kelola Produk</h1>
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    </div>

    <a href="{{ route('admin.produk.create') }}"
        class="inline-flex items-center gap-2 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-[11px] px-6 py-3 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
        <i class="fa-solid fa-plus text-xs"></i> Tambah Produk
    </a>
</div>

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
                    <th class="px-8 py-4 text-center">Total Stok</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach ($produk as $p)
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

                    {{-- 1. FIX KOLOM HARGA: SEJAJAR & PECAH PER VARIASI --}}
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
                    <td class="px-8 py-4">
                        <div class="flex flex-col gap-1.5 max-w-[200px] mx-auto">
                            @if($p->variasis && $p->variasis->count() > 0)
                                @foreach($p->variasis as $v)
                                    <div class="flex justify-between items-center h-6 text-[10px] uppercase font-medium">
                                        <span class="text-gray-500 tracking-wider">{{ $v->ukuran }} - {{ $v->warna }}</span>
                                        <span class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded font-bold">Stok: {{ $v->stok }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-[10px] text-gray-300 italic text-center block">Tidak ada variasi</span>
                            @endif
                        </div>
                    </td>

                    {{-- Total Stok Global --}}
                    <td class="px-8 py-4 text-center">
                        <span class="text-sm font-bold text-[#001f3f]">
                            {{ $p->variasis && $p->variasis->count() > 0 ? $p->variasis->sum('stok') : $p->stok }}
                        </span>
                    </td>

                    {{-- 2. FIX KOLOM STATUS: PECAH PER VARIASI (TERSEDIA / HABIS) --}}
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
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection