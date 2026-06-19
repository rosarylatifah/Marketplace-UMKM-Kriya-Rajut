@extends('layouts.admin')

@section('content')

<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pengajuan Pembatalan</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Pesanan yang diajukan pembeli untuk dibatalkan, menunggu keputusan admin.</p>
</div>

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

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Menunggu Keputusan ({{ count($pesanan_pengajuan) }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4">Detail Pesanan</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4 text-center">Hubungi</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_pengajuan as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    <td class="px-8 py-4">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</span>
                            <span class="text-[9px] text-gray-400 mt-1">
                                {{ $p->updated_at ? \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') : '-' }} WIB
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-semibold text-gray-700">{{ $p->nama_pembeli }}</td>
                    <td class="px-8 py-4 text-xs text-gray-600 max-w-[280px]">{{ $p->nama_barang }}</td>
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f]">
                        Rp{{ number_format($p->total, 0, ',', '.') }}
                    </td>
                    <td class="px-8 py-4 text-center">
                        @php
                            $nomorClean = preg_replace('/[^0-9]/', '', $p->no_hp ?? '');
                            if (str_starts_with($nomorClean, '0')) {
                                $nomorClean = '62' . substr($nomorClean, 1);
                            }
                            $nomorTujuan = empty($nomorClean) ? '628123456789' : $nomorClean;
                            $pesanWA = "Halo " . $p->nama_pembeli . ", terkait pengajuan pembatalan pesanan #" . $p->id_pesanan . ", mohon konfirmasi detailnya ya.";
                            $linkWA = "https://api.whatsapp.com/send?phone=" . $nomorTujuan . "&text=" . urlencode($pesanWA);
                        @endphp
                        <a href="{{ $linkWA }}" target="_blank" class="inline-flex items-center justify-center gap-2 border border-gray-200 px-3 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50">
                            <i class="fa-brands fa-whatsapp text-emerald-500"></i> WA
                        </a>
                    </td>
                    <td class="px-8 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <form action="{{ route('admin.pesanan.setujuiBatal', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('Setujui pembatalan pesanan ini? Stok akan dikembalikan.')"
                                    class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-all">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.pesanan.tolakBatal', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('Tolak pengajuan pembatalan ini?')"
                                    class="text-[10px] font-bold uppercase tracking-widest text-red-500 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <p class="text-sm text-gray-400 uppercase tracking-widest">Tidak ada pengajuan pembatalan saat ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection