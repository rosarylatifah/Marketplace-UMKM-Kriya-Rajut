@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-10">
    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Kelola</p>
    <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pesanan Masuk</h1>
    <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    <p class="text-sm text-gray-400 mt-3">Kelola dan pantau pesanan dari pelanggan.</p>
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
            <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.2em]">Daftar Pesanan ({{ count($pesanan_masuk) }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#F3F5F1] text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID Pesanan</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4 text-center">Detail Barang</th>
                    <th class="px-8 py-4">Total</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesanan_masuk as $p)
                <tr class="hover:bg-[#F3F5F1] transition-colors duration-150">
                    
                    {{-- 1. ID Pesanan & Waktu Order --}}
                    <td class="px-8 py-4 vertical-align-middle">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-[#001f3f] uppercase tracking-widest">{{ $p->id_pesanan }}</span>
                            <span class="text-[9px] text-gray-400 mt-1">
                                {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') : 'Waktu tdk tersedia' }} WIB
                            </span>
                        </div>
                    </td>

                    {{-- 2. Nama Pembeli --}}
                    <td class="px-8 py-4 text-sm font-semibold text-gray-700 vertical-align-middle">
                        {{ $p->nama_pembeli }}
                    </td>

                    {{-- 3. Detail Barang (Fix Multi-Produk Borongan) --}}
                    <td class="px-6 py-4 vertical-align-middle">
                        <div class="flex flex-col gap-3 items-center justify-center">
                            @php
                                // Memecah string produk jika pembeli membeli lebih dari 1 jenis barang (dipisahkan koma)
                                $items = explode(',', $p->nama_barang);
                            @endphp

                            @foreach($items as $item)
                                @php
                                    $item = trim($item); // Bersihkan spasi di awal/akhir
                                    
                                    // 1. Ambil Nama Produk Utama
                                    $nama_produk = Str::contains($item, '(') ? trim(Str::before($item, '(')) : $item;
                                    
                                    // 2. Ambil Kuantitas (Mencari pola x1, x2, dst)
                                    $qty = '1';
                                    if (Str::contains($item, '(x')) {
                                        $qty = Str::between($item, '(x', ')');
                                    } elseif (Str::contains($item, 'x')) {
                                        $qty = trim(Str::afterLast($item, 'x'));
                                    }
                                    
                                    // 3. Ambil Variasi & Bersihkan dari Karakter Sampah (Kurung Nyasar / Qty)
                                    $variasi = null;
                                    if (Str::contains($item, '(')) {
                                        // Ambil string di dalam kurung pertama
                                        $variasi = Str::between($item, '(', ')');
                                        
                                        // Jaga-jaga kalau string masih mengandung kurung tutup akibat explode multi-produk
                                        if (Str::contains($variasi, ')')) {
                                            $variasi = Str::before($variasi, ')');
                                        }
                                        
                                        // Bersihkan kalau teks kuantitas ikut kesedot masuk ke variasi
                                        if (Str::contains($variasi, 'x')) {
                                            $variasi = trim(Str::before($variasi, 'x'));
                                        }
                                    }
                                    $variasi = trim($variasi);
                                @endphp

                                <div class="flex items-center gap-3 w-full max-w-[280px] bg-gray-50/50 p-2 rounded-lg border border-gray-100">
                                    {{-- Logo Inisial Bulat --}}
                                    <div class="w-8 h-8 bg-[#001f3f] text-white rounded-md flex-shrink-0 flex items-center justify-center text-[10px] font-bold uppercase tracking-wider">
                                        {{ strtoupper(substr($nama_produk, 0, 2)) }}
                                    </div>

                                    {{-- Detail Konten Produk --}}
                                    <div class="flex flex-col flex-1 min-w-0 text-left">
                                        <span class="text-[11px] font-bold text-gray-800 uppercase tracking-wide truncate">
                                            {{ $nama_produk }}
                                        </span>
                                        
                                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                            @if($variasi && !empty(trim($variasi)))
                                                <span class="inline-block bg-white text-gray-500 text-[8px] font-medium px-1.5 py-0.5 rounded border border-gray-200 uppercase tracking-tight max-w-[120px] truncate">
                                                    {{ trim($variasi) }}
                                                </span>
                                            @endif
                                            <span class="text-[10px] font-bold text-[#001f3f]">
                                                x{{ $qty }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>

                    {{-- 4. Total Bayar --}}
                    <td class="px-8 py-4 text-sm font-bold text-[#001f3f] vertical-align-middle">
                        Rp{{ number_format($p->total, 0, ',', '.') }}
                    </td>

                    {{-- 5. Status Dropdown --}}
                    <td class="px-8 py-4 vertical-align-middle">
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

                    {{-- 6. Aksi Hapus --}}
                    <td class="px-8 py-4 vertical-align-middle">
                        <div class="flex items-center justify-center">
                            <form action="{{ route('pesanan.hapus', $p->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin mau hapus pesanan ini?')"
                                    class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-lg transition-all duration-150">
                                    <i class="fa-solid fa-trash text-xs"></i> Hapus
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