@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Konfirmasi Pesanan</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Periksa bukti transaksi dan validasi pesanan awal sebelum masuk antrean produksi.</p>
</div>

{{-- Alert Success Notifikasi --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold uppercase tracking-wider">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

    {{-- Table Header + Form Search --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-8 py-5 border-b border-gray-100 gap-4">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-1">Total</p>
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Perlu Dikonfirmasi ({{ count($pesanan_konfirmasi) }})</h2>
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

    {{-- Info Keterangan Keyword Pencarian --}}
    @if(request('search'))
        <div class="px-8 pt-4 text-xs text-gray-400 uppercase tracking-wider font-medium">
            Menampilkan hasil pencarian untuk: <span class="text-[#001f3f] font-bold">"{{ request('search') }}"</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4">Detail Pesanan</th>
                    <th class="px-8 py-4">Bukti Pembayaran</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4 text-center">Status Saat Ini</th>
                    <th class="px-8 py-4 text-center">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_konfirmasi as $index => $p)
                <tr id="{{ $p->id_pesanan }}" class="hover:bg-[#F3F5F1] {{ request('search') == $p->id_pesanan ? 'bg-amber-50/70' : '' }} transition-colors duration-150">
                    
                    {{-- 1. ID Pesanan & Waktu Order --}}
                    <td class="px-8 py-4 align-middle">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">#{{ $p->id_pesanan }}</span>
                            <span class="text-[9px] text-gray-400 mt-1">
                                {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') : 'Waktu tdk tersedia' }} WIB
                            </span>
                        </div>
                    </td>

                    {{-- 2. Nama Pembeli --}}
                    <td class="px-8 py-4 text-sm font-semibold text-gray-700 align-middle">
                        {{ $p->nama_pembeli }}
                    </td>

                    {{-- 3. Detail Barang + MODAL POPUP --}}
                    <td class="px-8 py-4 align-middle">
                        <button data-modal-target="modal-konfirmasi-{{ $index }}" data-modal-toggle="modal-konfirmasi-{{ $index }}" type="button" class="text-left block group">
                            <div class="flex items-center gap-3 bg-gray-50/50 p-2 rounded-lg border border-gray-100 group-hover:border-[#001f3f] group-hover:bg-white transition-all duration-150">
                                <div class="w-8 h-8 bg-[#001f3f] text-white rounded-md flex-shrink-0 flex items-center justify-center text-[10px] font-bold uppercase">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wide line-clamp-1 max-w-[250px]">
                                        {{ $p->nama_barang }}
                                    </span>
                                    <span class="text-[9px] text-blue-600 font-semibold underline mt-0.5">Klik untuk Detail Ringkasan</span>
                                </div>
                            </div>
                        </button>

                        {{-- MODAL POPUP DETAIL RINGKASAN PESANAN --}}
                        <div id="modal-konfirmasi-{{ $index }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
                            <div class="relative w-full max-w-lg h-auto">
                                <div class="relative bg-white border border-gray-300 p-6 shadow-2xl text-left rounded-xl">
                                    
                                    <div class="flex flex-col gap-4">
                                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                            <div>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest block">Rincian Transaksi Awal</span>
                                                <h3 class="text-sm font-bold text-[#001f3f] font-mono mt-0.5">#{{ $p->id_pesanan }}</h3>
                                            </div>
                                            <button data-modal-hide="modal-konfirmasi-{{ $index }}" type="button" class="text-gray-400 hover:text-red-500">
                                                <i class="fa-solid fa-xmark text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="space-y-3 text-xs">
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Produk yang Dipesan:</span>
                                                <p class="text-gray-800 font-bold uppercase bg-gray-50 p-2.5 rounded border border-gray-100 mt-1 leading-relaxed">
                                                    {{ $p->nama_barang }}
                                                </p>
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

                                            {{-- Menampilkan No Handphone Dinamis --}}
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">No. Handphone / WA:</span>
                                                <span class="text-sm font-mono font-semibold text-gray-800">
                                                    {{ $p->no_hp ?? ($p->user->no_hp ?? 'Belum mengisi nomor telepon') }}
                                                </span>
                                            </div>

                                            {{-- Alamat Pengiriman --}}
                                            <div>
                                                <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Alamat Pengiriman:</span>
                                                <p class="text-gray-700 bg-gray-50/70 p-2.5 rounded border border-gray-100 mt-1 leading-relaxed font-medium">
                                                    {{ $p->user->alamat ?? ($p->alamat ?? 'Alamat belum diatur oleh pembeli.') }}
                                                </p>
                                            </div>

                                            {{-- Desain Struk Total Harga (Bentuk Row) --}}
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

                                        {{-- Tombol Hubungi Pembeli WhatsApp --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            @php
                                                // Ambil nomor HP dari data pesanan/user
                                                $nomorRaw = $p->no_hp ?? ($p->user->no_hp ?? '');
                                                
                                                // Bersihkan karakter non-angka
                                                $nomorClean = preg_replace('/[^0-9]/', '', $nomorRaw);

                                                // Normalisasi format 08xx ke kode negara 628xx
                                                if (str_starts_with($nomorClean, '0')) {
                                                    $nomorClean = '62' . substr($nomorClean, 1);
                                                }

                                                // Cadangan darurat jika data kosong
                                                $nomorTujuan = empty($nomorClean) ? '628123456789' : $nomorClean;

                                                $pesanWA = "Halo " . $p->nama_pembeli . ", kami dari Admin Kriya Rajut (Namonic) ingin mengonfirmasi pesanan Anda dengan ID #" . $p->id_pesanan . ". Apakah data transaksinya sudah sesuai?";
                                                $linkWA = "https://api.whatsapp.com/send?phone=" . $nomorTujuan . "&text=" . urlencode($pesanWA);
                                            @endphp
                                            
                                            {{-- Jika nomor HP kosong, kasih warning --}}
                                            @if(empty($nomorClean))
                                                <div class="text-center text-[10px] text-amber-600 font-semibold uppercase tracking-wider bg-amber-50 p-2 rounded-lg border border-amber-200 mb-2">
                                                    ⚠️ Pembeli belum mengisi nomor HP / WhatsApp
                                                </div>
                                            @endif

                                            {{-- FIX: Teks Tombol diganti menjadi Hubungi Pembeli --}}
                                            <a href="{{ $linkWA }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider py-3 rounded-lg transition-all shadow-md">
                                                <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Pembeli via WhatsApp
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Kolom Tambahan: Bukti Pembayaran --}}
                    <td class="px-8 py-4 align-middle">
                        @if($p->bukti_pembayaran)
                            <a href="{{ asset('images/bukti/' . $p->bukti_pembayaran) }}" target="_blank" class="inline-block group">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden group-hover:border-[#001f3f] transition-all flex items-center justify-center relative shadow-sm">
                                    <img src="{{ asset('images/bukti/' . $p->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-150">
                                    {{-- Efek Hover Overlay Kaca Pembesar --}}
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                                    </div>
                                </div>
                            </a>
                        @else
                            <span class="inline-block text-[9px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded-md">
                                ⏳ Belum Kirim
                            </span>
                        @endif
                    </td>

                    {{-- 4. Total Bayar (Dikurangi Ongkir agar tampil harga produk saja) --}}
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f] align-middle">
                        Rp{{ number_format(($p->total - ($p->ongkir ?? 0)), 0, ',', '.') }}
                    </td>

                    {{-- 5. Status Awal Badges --}}
                    <td class="px-8 py-4 align-middle text-center">
                        <span class="inline-block whitespace-nowrap text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full border bg-amber-50 text-amber-600 border-amber-200">
                            {{ $p->status }}
                        </span>
                    </td>

                    {{-- 6. Dropdown Tunggal Otomatis Proses --}}
                    <td class="px-8 py-4 align-middle text-center">
                        <div class="flex justify-center items-center">
                            <form action="{{ route('pesanan.update', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <select name="status" onchange="if(this.value === 'SEDANG DIPROSES' ? confirm('Konfirmasi pesanan ini dan pindahkan ke Pesanan Masuk?') : true) { this.form.submit(); }" 
                                        class="text-[11px] font-bold uppercase tracking-wider bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-700 focus:border-[#001f3f] focus:outline-none transition-all cursor-pointer">
                                    <option value="BELUM KONFIRMASI" {{ $p->status == 'BELUM KONFIRMASI' ? 'selected' : '' }}>❌ Belum Konfirmasi</option>
                                    <option value="SEDANG DIPROSES">✔️ Sudah Konfirmasi</option>
                                </select>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i class="fa-solid fa-inbox text-3xl text-gray-200"></i>
                            <p class="text-sm text-gray-400 uppercase tracking-widest">Tidak ada pesanan yang perlu dikonfirmasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection