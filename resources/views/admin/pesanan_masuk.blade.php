@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-bold text-black tracking-tight">Pesanan Masuk</h1>
    <p class="text-gray-600 font-medium">Kelola dan pantau pesanan dari pelanggan Namonic 🧶</p>
</div>

<div class="mb-4">
    <h2 class="text-lg font-bold text-black uppercase tracking-wider">
    Daftar Pesanan ({{ count($pesanan_masuk) }})
    </h2>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left border border-gray-300">
        <thead class="bg-pink-600">
            <tr class="text-white uppercase text-xs font-black tracking-widest">
                <th class="px-4 py-4">ID Pesanan</th>
                <th class="px-4 py-4">Nama Pembeli</th>
                <th class="px-4 py-4 text-center">Nama Barang</th>
                <th class="px-4 py-4">Total</th>
                <th class="px-4 py-4">Status</th>
                <th class="px-4 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white text-black font-medium">
            @forelse ($pesanan_masuk as $p)
            <tr class="hover:bg-pink-50 transition-colors">
                <td class="px-4 py-4 font-bold uppercase">{{ $p->id_pesanan }}</td>
                <td class="px-4 py-4">{{ $p->nama_pembeli }}</td>
                <td class="px-4 py-4 text-center">{{ $p->nama_barang }}</td>
                <td class="px-4 py-4">Rp{{ number_format($p->total, 0, ',', '.') }}</td>
                <td class="px-4 py-4">
                    {{-- FORM EDIT STATUS --}}
                    <form action="{{ route('pesanan.update', $p->id) }}" method="POST" id="form-status-{{ $p->id }}">
                        @csrf
                        @method('PUT')

                        <select name="status" onchange="document.getElementById('form-status-{{ $p->id }}').submit()" 
                            class="border border-black px-2 py-1 text-[10px] uppercase font-bold cursor-pointer outline-none
                            {{ $p->status == 'SEDANG DIPROSES' ? 'bg-yellow-200' : '' }}
                            {{ $p->status == 'DALAM PERJALANAN' ? 'bg-blue-200' : '' }}
                            {{ $p->status == 'SELESAI' ? 'bg-green-200' : '' }}">
                            
                            <option value="SEDANG DIPROSES" {{ $p->status == 'SEDANG DIPROSES' ? 'selected' : '' }}>SEDANG DIPROSES</option>
                            <option value="DALAM PERJALANAN" {{ $p->status == 'DALAM PERJALANAN' ? 'selected' : '' }}>DALAM PERJALANAN</option>
                            <option value="SELESAI" {{ $p->status == 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                        </select>

                    </form>
                </td>

                <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('pesanan.hapus', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin mau hapus pesanan ini?')" 
                                    class="p-1.5 bg-white border border-black hover:bg-red-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                    Tidak ada pesanan masuk... 🧶
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection