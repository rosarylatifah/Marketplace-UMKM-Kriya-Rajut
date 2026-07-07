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

    {{-- Table Header + Form Search --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-8 py-5 border-b border-gray-100 gap-4">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Menunggu Keputusan ({{ count($pesanan_pengajuan) }})</h2>
        </div>
        
        {{-- Form Pencarian Minimalis --}}
        <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID / Nama Pembeli..." 
                   class="text-xs border border-gray-200 px-4 py-2 rounded-lg outline-none focus:border-[#001f3f] transition-all min-w-[200px]">
            <button type="submit" class="bg-[#001f3f] text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:opacity-90 transition-all">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ url()->current() }}" class="text-[10px] text-red-500 font-bold uppercase tracking-wider underline ml-1">Reset</a>
            @endif
        </form>
        
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4">Detail Pesanan</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_pengajuan as $index => $p)
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

                    {{-- 3. Detail Barang + MODAL POPUP --}}
                    <td class="px-8 py-4 align-middle">
                        <button data-modal-target="modal-masuk-{{ $index }}" data-modal-toggle="modal-masuk-{{ $index }}" type="button" class="text-left block group">
                            <div class="flex items-center gap-3 bg-gray-50/50 p-2 rounded-lg border border-gray-100 group-hover:border-[#001f3f] group-hover:bg-white transition-all duration-150">
                                <div class="w-8 h-8 bg-[#001f3f] text-white rounded-md flex-shrink-0 flex items-center justify-center text-[10px] font-bold uppercase">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wide line-clamp-1 max-w-[250px]">
                                        {{ $p->nama_barang }}
                                    </span>
                                    <span class="text-[9px] text-blue-600 font-semibold underline mt-0.5">Klik untuk Detail</span>
                                </div>
                            </div>
                        </button>

                        {{-- MODAL POPUP DETAIL PESANAN --}}
                        <div id="modal-masuk-{{ $index }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
                            <div class="relative w-full max-w-lg h-auto">
                                <div class="relative bg-white border border-gray-300 p-6 shadow-2xl text-left rounded-xl">
                                    <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                        <h3 class="text-sm font-bold text-[#001f3f] font-mono">#{{ $p->id_pesanan }}</h3>
                                        <button data-modal-hide="modal-masuk-{{ $index }}" type="button" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    
                                    <div class="space-y-3 text-xs">
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Produk yang Dipesan:</span>
                                                
                                                <div class="relative w-full border border-gray-100 rounded-lg overflow-hidden bg-gray-50/50">
                                                    <div class="max-h-[250px] overflow-y-auto">
                                                        <table class="w-full text-left border-collapse">
                                                            <thead class="sticky top-0 bg-gray-100 z-10">
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
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Nama Pembeli</span>
                                                    <span class="text-sm font-semibold text-gray-800">{{ $p->nama_pembeli }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Email</span>
                                                    <span class="text-sm font-mono text-gray-600">{{ $p->email }}</span>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">No. Handphone / WA:</span>
                                                <span class="text-sm font-mono font-semibold text-gray-800">
                                                    {{ $p->no_hp ?? optional($p->user)->no_hp ?? 'Belum mengisi nomor telepon' }}
                                                </span>
                                            </div>

                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Alamat Pengiriman:</span>
                                                <p class="text-gray-700 bg-gray-50/70 p-2.5 rounded border border-gray-100 mt-1 leading-relaxed font-medium">
                                                    {{ $p->alamat ?? optional($p->user)->alamat ?? 'Alamat belum diatur oleh pembeli.' }}
                                                </p>
                                            </div>

                                            <div class="space-y-2 pt-3 border-t border-gray-100">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Harga Barang</span>
                                                    <span class="text-xs font-semibold text-gray-700">
                                                        Rp{{ number_format(($p->total - ($p->ongkir ?? 0)), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Biaya Ongkir</span>
                                                    <span class="text-xs font-medium text-gray-600">
                                                        + Rp{{ number_format($p->ongkir ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg border border-gray-100 mt-1">
                                                    <span class="text-[10px] text-[#001f3f] uppercase tracking-wider font-bold">Total Tagihan</span>
                                                    <span class="text-sm font-bold text-[#001f3f]">
                                                        Rp{{ number_format($p->total, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    {{-- Tombol Hubungi WhatsApp Hubungi Pembeli (Pengajuan Batal) --}}
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        @php
                                            $nomorTujuan = $p->no_hp ?? ($p->user->no_hp ?? '628123456789'); 
                                            
                                            if (str_starts_with($nomorTujuan, '0')) {
                                                $nomorTujuan = '62' . substr($nomorTujuan, 1);
                                            }

                                            $pesanWA = "Halo " . $p->nama_pembeli . ", kami dari Admin Kriya Rajut (StitchySist). Terkait pemesanan ID " . $p->id_pesanan . " senilai Rp " . number_format($p->total, 0, ',', '.') . " sudah diajukan pembatalannya. Terima kasih atas pengertiannya. Jika ada pertanyaan, silakan hubungi kami kembali.";
                                            $linkWA = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $nomorTujuan) . "&text=" . urlencode($pesanWA);
                                        @endphp
                                        
                                        <a href="{{ $linkWA }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider py-3 rounded-lg transition-all shadow-md">
                                            <i class="fa-brands fa-whatsapp text-base"></i> Kirim Pesan via WhatsApp
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f]">
                        Rp{{ number_format(($p->total - ($p->ongkir ?? 0)), 0, ',', '.') }}
                    </td>
                   
                    <td class="px-8 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <form action="{{ route('admin.pesanan.setujuiBatal', $p->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="text-[10px] font-bold uppercase text-emerald-600 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-all">Setujui</button>
                            </form>
                            <form action="{{ route('admin.pesanan.tolakBatal', $p->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="text-[10px] font-bold uppercase text-red-500 bg-red-50 border border-red-300 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-16 text-center text-gray-400">Tidak ada pengajuan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection