@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-black uppercase tracking-tight">Kelola Produk</h1>
        <p class="text-gray-500 text-sm">Daftar produk rajutan Namonic</p>
    </div>

    <a href="{{ route('admin.produk.create') }}">
        <button class="text-white bg-pink-600 hover:bg-pink-600 font-bold border border-black rounded-none text-xs px-5 py-2.5 uppercase tracking-widest transition-colors">
            + Tambah Produk
        </button>
    </a>
</div>

<div class="border border-black bg-white overflow-hidden">
    <table class="w-full text-sm text-left text-black">
        <thead class="text-[11px] text-white uppercase bg-pink-600">
            <tr class="font-bold tracking-widest">
                <th class="px-6 py-4">Produk</th>
                <th class="px-6 py-4 text-center">Kategori</th>
                <th class="px-6 py-4 text-center">Harga</th>
                <th class="px-6 py-4 text-center">Stok</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @foreach ($produk as $p)
            <tr class="hover:bg-pink-50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 border border-black rounded-none flex-shrink-0 overflow-hidden">
                            {{-- Path diganti ke 'images/' sesuai folder di public --}}
                            <img src="{{ asset('images/' . $p->foto) }}" alt="{{ $p->nama }}" class="object-cover w-full h-full" onerror="this.src='https://placehold.co/100x100?text=Foto'">
                        </div>
                        <span class="font-semibold uppercase text-xs tracking-tight">{{ $p->nama }}</span>
                    </div>
                </td>

                {{-- Nama variabel diganti jadi 'kategori' --}}
                <td class="px-6 py-4 text-center text-gray-600 font-medium uppercase text-[10px]">{{ $p->kategori }}</td>
                
                <td class="px-6 py-4 text-center font-bold text-pink-600">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-center font-bold">{{ $p->stok }}</td>
                
                <td class="px-6 py-4 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $p->stok > 0 ? 'text-gray-600' : 'text-red-500' }}">
                        {{ $p->stok > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </td>
                
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">

                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.produk.edit', $p->id) }}" class="p-1.5 bg-white border border-black hover:bg-gray-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus produk {{ $p->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 bg-white border border-black rounded-none hover:bg-red-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection