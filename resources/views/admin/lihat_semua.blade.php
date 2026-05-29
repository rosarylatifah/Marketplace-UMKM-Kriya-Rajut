@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Panel Admin</p>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Semua Aktivitas Pesanan</h1>
        <a href="/admin/dashboard" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-colors flex items-center gap-1">
            ← Kembali ke Dashboard
        </a>
    </div>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Menampilkan seluruh riwayat transaksi Kriya Rajut.</p>
</div>

{{-- Tabel Riwayat Aktivitas --}}
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-400">
                    <th class="pb-4 font-bold">ID Pesanan</th>
                    <th class="pb-4 font-bold">Tanggal</th>
                    <th class="pb-4 font-bold">Nama Pembeli</th>
                    <th class="pb-4 font-bold">Detail Barang</th>
                    <th class="pb-4 font-bold">Total Pembayaran</th>
                    <th class="pb-4 font-bold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($semua_pesanan as $p)
                <tr class="hover:bg-[#F3F5F1]/50 transition-colors duration-150">
                    <td class="py-4 font-bold text-[#001f3f] uppercase tracking-wider vertical-align-middle">{{ $p->id_pesanan }}</td>
                    <td class="py-4 text-gray-400 vertical-align-middle">{{ $p->created_at->format('d/m/Y H:i') }} WIB</td>
                    <td class="py-4 text-gray-700 font-medium vertical-align-middle">{{ $p->nama_pembeli }}</td>
                    
                    {{-- Detail Barang (VERSI BARU: Polosan Minimalis, Gak Pake Kotak, Gak Kepotong) --}}
                    <td class="py-4 vertical-align-middle">
                        <div class="flex flex-col gap-1.5 items-start justify-center max-w-xs">
                            @php
                                $items = explode(',', $p->nama_barang);
                            @endphp

                            @foreach($items as $item)
                                @php
                                    $item = trim($item);
                                    
                                    // 1. Ambil Nama Produk
                                    $nama_produk = Str::contains($item, '(') ? trim(Str::before($item, '(')) : $item;
                                    
                                    // 2. Ambil Kuantitas
                                    $qty = '1';
                                    if (Str::contains($item, '(x')) {
                                        $qty = Str::between($item, '(x', ')');
                                    } elseif (Str::contains($item, 'x')) {
                                        $qty = trim(Str::afterLast($item, 'x'));
                                    }
                                    
                                    // 3. Ambil Variasi
                                    $variasi = null;
                                    if (Str::contains($item, '(')) {
                                        $variasi = Str::between($item, '(', ')');
                                        if (Str::contains($variasi, ')')) {
                                            $variasi = Str::before($variasi, ')');
                                        }
                                        if (Str::contains($variasi, 'x')) {
                                            $variasi = trim(Str::before($variasi, 'x'));
                                        }
                                    }
                                    $variasi = trim($variasi);
                                @endphp

                                {{-- Teks Polosan Berbaris Kebawah --}}
                                <div class="text-left text-gray-700 leading-relaxed">
                                    <span class="font-bold uppercase text-[11px]">{{ $nama_produk }}</span>
                                    @if($variasi && !empty($variasi))
                                        <span class="text-gray-400 text-[10px]">({{ $variasi }})</span>
                                    @endif
                                    <span class="font-bold text-[#001f3f] text-[10px] ml-1">x{{ $qty }}</span>
                                </div>
                            @endforeach
                        </div>
                    </td>

                    <td class="py-4 font-semibold text-gray-800 vertical-align-middle">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="py-4 text-center vertical-align-middle">
                        @if($p->status == 'SEDANG DIPROSES')
                            <span class="inline-block bg-amber-50 text-amber-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-amber-100">Diproses</span>
                        @elseif($p->status == 'DALAM PERJALANAN')
                            <span class="inline-block bg-blue-50 text-blue-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-blue-100">Dikirim</span>
                        @elseif($p->status == 'SELESAI')
                            <span class="inline-block bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-emerald-100">Selesai</span>
                        @else
                            <span class="inline-block bg-gray-50 text-gray-500 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-gray-100">{{ $p->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center text-gray-400 uppercase tracking-widest">
                        <i class="fa-solid fa-folder-open text-3xl text-gray-200 mb-3 block"></i>
                        Belum ada riwayat aktivitas pesanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection