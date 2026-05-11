@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pesanan Masuk</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Kelola dan pantau pesanan dari pelanggan.</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    {{-- Table Header --}}
    <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Daftar Pesanan ({{ count($pesanan_masuk) }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4 text-center">Nama Barang</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_masuk as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    <td class="px-8 py-4 text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</td>
                    <td class="px-8 py-4 text-sm text-gray-700">{{ $p->nama_pembeli }}</td>
                    <td class="px-8 py-4 text-sm text-gray-700 text-center">{{ $p->nama_barang }}</td>
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f]">Rp{{ number_format($p->total, 0, ',', '.') }}</td>

                    {{-- Status --}}
                    <td class="px-8 py-4">
                        <form action="{{ route('pesanan.update', $p->id) }}" method="POST" id="form-status-{{ $p->id }}">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="document.getElementById('form-status-{{ $p->id }}').submit()"
                                class="text-[10px] font-bold uppercase tracking-widest border border-gray-200 rounded-lg px-3 py-2 cursor-pointer outline-none transition-all duration-150
                                {{ $p->status == 'SEDANG DIPROSES' ? 'bg-amber-50 text-amber-600 border-amber-200' : '' }}
                                {{ $p->status == 'DALAM PERJALANAN' ? 'bg-blue-50 text-blue-600 border-blue-200' : '' }}
                                {{ $p->status == 'SELESAI' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : '' }}">
                                <option value="SEDANG DIPROSES" {{ $p->status == 'SEDANG DIPROSES' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="DALAM PERJALANAN" {{ $p->status == 'DALAM PERJALANAN' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                <option value="SELESAI" {{ $p->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-8 py-4">
                        <div class="flex items-center justify-center">
                            <form action="{{ route('pesanan.hapus', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin mau hapus pesanan ini?')"
                                    class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-lg transition-all duration-150">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-inbox text-3xl text-gray-200"></i>
                            <p class="text-sm text-gray-400 uppercase tracking-widest">Tidak ada pesanan masuk</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection