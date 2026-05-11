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

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">Produk</th>
                    <th class="px-8 py-4 text-center">Kategori</th>
                    <th class="px-8 py-4 text-center">Harga</th>
                    <th class="px-8 py-4 text-center">Stok</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach ($produk as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#F3F5F1] border border-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                                <img src="{{ asset('images/' . $p->foto) }}" alt="{{ $p->nama }}" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/100x100?text=Foto'">
                            </div>
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->nama }}</span>
                        </div>
                    </td>

                    <td class="px-8 py-4 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $p->kategori }}</span>
                    </td>

                    <td class="px-8 py-4 text-center">
                        <span class="text-sm font-bold text-[#001f3f]">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                    </td>

                    <td class="px-8 py-4 text-center">
                        <span class="text-sm font-bold text-[#001f3f]">{{ $p->stok }}</span>
                    </td>

                    <td class="px-8 py-4 text-center">
                        @if($p->stok > 0)
                            <span class="inline-block bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Tersedia</span>
                        @else
                            <span class="inline-block bg-red-50 text-red-500 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">Habis</span>
                        @endif
                    </td>

                    <td class="px-8 py-4">
                        <div class="flex items-center justify-center gap-2">

                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.produk.edit', $p->id) }}"
                                class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-[#001f3f] hover:bg-[#F3F5F1] border border-gray-200 px-3 py-2 rounded-lg transition-all duration-150">
                                <i class="fa-solid fa-pen text-xs"></i> Edit
                            </a>

                            {{-- Tombol Hapus --}}
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