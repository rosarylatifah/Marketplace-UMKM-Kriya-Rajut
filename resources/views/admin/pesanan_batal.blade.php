@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pesanan Dibatalkan</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Daftar pesanan kriya rajut yang telah dibatalkan oleh pembeli atau sistem.</p>
</div>

{{-- Alert Success Notifikasi --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    {{-- Table Header --}}
    <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Daftar Pembatalan ({{ count($pesanan_batal) }})</h2>
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
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_batal as $index => $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    
                    {{-- 1. ID Pesanan --}}
                    <td class="px-8 py-4">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</span>
                            <span class="text-[9px] text-gray-400 mt-1">
                                {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') : 'Waktu tdk tersedia' }} WIB
                            </span>
                        </div>
                    </td>

                    {{-- 2. Nama Pembeli --}}
                    <td class="px-8 py-4 text-sm font-semibold text-gray-700">
                        {{ $p->nama_pembeli }}
                    </td>

                    {{-- 3. Detail Barang dengan Pop-up Modal --}}
                    <td class="px-8 py-4">
                        <button data-modal-target="modal-batal-{{ $index }}" data-modal-toggle="modal-batal-{{ $index }}" type="button" class="text-left block group">
                            <div class="flex items-center gap-3 bg-gray-50/50 p-2 rounded-lg border border-gray-100 group-hover:border-[#001f3f] group-hover:bg-white transition-all duration-150">
                                <div class="w-8 h-8 bg-[#001f3f] text-white rounded-md flex-shrink-0 flex items-center justify-center text-[10px] font-bold uppercase">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wide line-clamp-1 max-w-[250px]">
                                        {{ $p->nama_barang }}
                                    </span>
                                    <span class="text-[9px] text-blue-600 font-semibold underline mt-0.5">Klik untuk Detail & Refund</span>
                                </div>
                            </div>
                        </button>

                        {{-- MODAL POPUP RAPI DENGAN STRUKTUR TABEL & GAMBAR --}}
                        <div id="modal-batal-{{ $index }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
                            <div class="relative w-full max-w-lg h-auto">
                                <div class="relative bg-white border border-gray-300 p-6 shadow-2xl text-left rounded-xl">
                                    
                                    <div class="flex flex-col gap-4">
                                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                            <div>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest block">Rincian Pembatalan</span>
                                                <h3 class="text-sm font-bold text-[#001f3f] font-mono mt-0.5">{{ $p->id_pesanan }}</h3>
                                            </div>
                                            <button data-modal-hide="modal-batal-{{ $index }}" type="button" class="text-gray-400 hover:text-red-500">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="space-y-4 text-xs">
                                            {{-- BAGIAN BARANG YANG DIBATALKAN --}}
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold mb-2">Produk yang Dibatalkan:</span>
                                                
                                                <div class="border border-gray-100 rounded-lg overflow-hidden bg-gray-50/50">
                                                    <table class="w-full text-left border-collapse">
                                                        <thead>
                                                            <tr class="bg-gray-100 text-[9px] uppercase tracking-wider text-gray-500 font-bold border-b border-gray-200">
                                                                <th class="px-4 py-2.5">Gambar & Produk</th>
                                                                <th class="px-4 py-2.5 text-center w-16">Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 bg-white text-xs">
                                                            @php
                                                                // Memisahkan multi-produk berdasarkan koma
                                                                $items = explode(',', $p->nama_barang);
                                                            @endphp

                                                            @foreach ($items as $item)
                                                                @php
                                                                    $item = trim($item);
                                                                    if (empty($item)) continue;

                                                                    // 1. Ekstrak Quantity (x1 atau X1)
                                                                    $qty = '1';
                                                                    if (preg_match('/\(X(\d+)\)/i', $item, $matchQty)) {
                                                                        $qty = $matchQty[1];
                                                                        $itemClean = trim(preg_replace('/\(X\d+\)/i', '', $item));
                                                                    } else {
                                                                        $itemClean = $item;
                                                                    }

                                                                    // 2. Ekstrak Nama Produk Utama dan Teks Varian
                                                                    $namaProdukSaja = $itemClean;
                                                                    $varianSaja = '-';
                                                                    if (preg_match('/^([^(]+)\(([^)]+)\)/', $itemClean, $matchDetail)) {
                                                                        $namaProdukSaja = trim($matchDetail[1]);
                                                                        $varianSaja = trim($matchDetail[2]);
                                                                    }

                                                                    // 3. PENCARIAN SUPER FLEKSIBEL (ROBUST SEARCHING)
                                                                    $dbProduk = null;
                                                                    if (isset($semua_produk)) {
                                                                        $dbProduk = $semua_produk->first(function($prod) use ($namaProdukSaja, $varianSaja) {
                                                                            $namaDbClean = strtoupper(preg_replace('/\s+/', '', trim($prod->nama)));
                                                                            $namaCariClean = strtoupper(preg_replace('/\s+/', '', trim($namaProdukSaja)));
                                                                            $varianCariClean = strtoupper(preg_replace('/\s+/', '', trim($varianSaja)));

                                                                            $matchNama = ($namaDbClean === $namaCariClean || stripos($namaDbClean, $namaCariClean) !== false || stripos($namaCariClean, $namaDbClean) !== false);
                                                                            $matchVarianKeNamaDb = (!empty($varianSaja) && stripos($namaDbClean, $varianCariClean) !== false);

                                                                            return $matchNama || $matchVarianKeNamaDb;
                                                                        });
                                                                    }

                                                                    // 4. Ambil Jalur Gambar Produk / Gambar Varian dari Database
                                                                    $namaFileGambar = null;
                                                                    if ($dbProduk) {
                                                                        $dbVarian = $dbProduk->variasis->first(function($v) use ($varianSaja) {
                                                                            $warnaDb = strtoupper(preg_replace('/\s+/', '', trim($v->warna ?? '')));
                                                                            $ukuranDb = strtoupper(preg_replace('/\s+/', '', trim($v->ukuran ?? '')));
                                                                            $varianCari = strtoupper(preg_replace('/\s+/', '', trim($varianSaja)));

                                                                            $matchWarna = (!empty($warnaDb) && (stripos($varianCari, $warnaDb) !== false || stripos($warnaDb, $varianCari) !== false));
                                                                            $matchUkuran = (!empty($ukuranDb) && (stripos($varianCari, $ukuranDb) !== false || stripos($ukuranDb, $varianCari) !== false));

                                                                            return $matchWarna || $matchUkuran;
                                                                        });

                                                                        $namaFileGambar = ($dbVarian && !empty($dbVarian->foto)) ? $dbVarian->foto : $dbProduk->foto;
                                                                    }

                                                                    $pathFoto = $namaFileGambar ? asset('images/' . $namaFileGambar) : null;
                                                                @endphp

                                                                <tr class="hover:bg-gray-50/80 transition-colors">
                                                                    {{-- Kolom Gambar & Info Produk --}}
                                                                    <td class="px-4 py-3">
                                                                        <div class="flex items-center gap-3">
                                                                            {{-- Thumbnail Frame Gambar Produk --}}
                                                                            <div class="w-12 h-12 rounded border border-gray-200 bg-gray-50 flex-shrink-0 overflow-hidden flex items-center justify-center shadow-sm">
                                                                                @if($pathFoto)
                                                                                    <img src="{{ $pathFoto }}" alt="Produk" class="w-full h-full object-cover">
                                                                                @else
                                                                                    <div class="text-gray-400 text-center flex flex-col items-center justify-center">
                                                                                        <i class="fa-solid fa-image text-xs block mb-0.5"></i>
                                                                                        <span class="text-[7px] uppercase tracking-wider font-semibold">No Img</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                            {{-- Deskripsi Judul Produk & Tautan Dinamis Admin --}}
                                                                            <div class="flex flex-col min-w-0">
                                                                                @if ($dbProduk)
                                                                                    <a href="{{ route('admin.produk.edit', $dbProduk->id) }}" class="font-bold text-[#001f3f] hover:text-blue-600 hover:underline uppercase tracking-wide text-[11px] truncate">
                                                                                        {{ $namaProdukSaja }}
                                                                                    </a>
                                                                                @else
                                                                                    <span class="font-bold text-gray-700 uppercase tracking-wide text-[11px] truncate">
                                                                                        {{ $namaProdukSaja }}
                                                                                    </span>
                                                                                @endif
                                                                                
                                                                                <span class="text-[9px] text-gray-400 font-medium uppercase mt-0.5">
                                                                                    Varian: <span class="text-gray-600 font-semibold">{{ $varianSaja }}</span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Kolom Quantity Belanja --}}
                                                                    <td class="px-4 py-3 text-center font-bold text-gray-800 align-middle text-sm">
                                                                        x{{ $qty }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Nama Pemesan</span>
                                                    <span class="text-sm font-semibold text-gray-800">{{ $p->nama_pembeli }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Email</span>
                                                    <span class="text-sm font-mono text-gray-600">{{ $p->email }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 pt-1">
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Total Dana Terbayar</span>
                                                    <span class="text-sm font-bold text-[#001f3f]">Rp{{ number_format($p->total, 0, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Biaya Ongkir</span>
                                                    <span class="text-sm font-medium text-gray-600">Rp{{ number_format($p->ongkir ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tombol Hubungi WhatsApp --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            @php
                                                $nomorTujuan = $p->no_hp ?? '628123456789';
                                                $pesanWA = "Halo " . $p->nama_pembeli . ", kami dari Admin Kriya Rajut ingin mengonfirmasi pengembalian dana untuk ID Pesanan " . $p->id_pesanan . " sebesar Rp " . number_format($p->total, 0, ',', '.') . " yang dibatalkan. Mohon kirimkan nomor rekening Anda. Terima kasih.";
                                                $linkWA = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $nomorTujuan) . "&text=" . urlencode($pesanWA);
                                            @endphp
                                            
                                            <a href="{{ $linkWA }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider py-3 rounded-lg transition-all shadow-md">
                                                <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Pembeli via WhatsApp
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- 4. Total Bayar --}}
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f] align-middle">
                        Rp{{ number_format(($p->total - ($p->ongkir ?? 0)), 0, ',', '.') }}
                    </td>

                    {{-- 5. Status --}}
                    <td class="px-8 py-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest bg-red-50 text-red-600 border border-red-200 rounded-lg px-3 py-1.5 inline-block">
                            {{ $p->status }}
                        </span>
                    </td>

                    {{-- 6. Aksi Hapus Log --}}
                    <td class="px-8 py-4 text-center">
                        <form action="{{ route('pesanan.hapus', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus log riwayat pembatalan ini?')"
                                class="text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 border border-red-100 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all">
                                Hapus Log
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center">
                        <p class="text-sm text-gray-400 uppercase tracking-widest">Tidak ada data pesanan yang dibatalkan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection