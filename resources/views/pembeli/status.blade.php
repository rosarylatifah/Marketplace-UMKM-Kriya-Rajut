@extends('layouts.pembeli')

@section('content')
<div class="py-12 max-w-6xl mx-auto px-4 lg:px-0">
    {{-- Header Halaman --}}
    <div class="mb-10">
        <span class="text-[10px] tracking-[0.5em] text-gray-400 mb-2 block">PESANANMU</span>
        <h1 class="text-2xl font-bold text-[#001f3f] tracking-[0.2em]">STATUS PESANAN
        <div class="mt-4 h-px w-12 bg-[#001f3f]"></div>
    </div>

    {{-- Container Daftar Pesanan --}}
    <div class="space-y-4">
        @if(isset($pesanan) && count($pesanan) > 0)
            @foreach($pesanan as $id => $details)
            {{-- Card Melebar --}}
            <div class="bg-white border border-gray-100 p-6 flex flex-col md:flex-row items-center gap-6 shadow-sm hover:shadow-md transition-all">
                
                {{-- Foto Produk --}}
                <div class="w-24 h-24 bg-gray-50 border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                    <img src="{{ $details['foto'] }}" class="w-full h-full object-cover" alt="{{ $details['nama'] }}">
                </div>

                {{-- Info Produk --}}
                <div class="flex-grow flex flex-col justify-between py-1 text-left">
                    <div>
                        <h3 class="font-bold text-sm text-[#001f3f] uppercase tracking-widest">
                            {{ $details['nama'] }}
                        </h3>
                        
                       {{-- Tombol Lihat Detail --}}
<button 
    class="btn-lihat-detail mt-1 text-[10px] tracking-widest font-bold text-[#001f3f] hover:underline block text-left"
    data-nama="{{ $details['nama'] }}"
    {{-- Ganti 'jumlah' menjadi 'quantity' --}}
    data-total="{{ number_format($details['harga'] * ($details['quantity'] ?? 1), 0, ',', '.') }}"
    data-deskripsi="{{ $details['deskripsi'] ?? 'Tidak ada deskripsi.' }}"
    {{-- Ganti 'jumlah' menjadi 'quantity' --}}
    data-jumlah="{{ $details['quantity'] ?? 1 }}"
    data-foto="{{ $details['foto'] }}">
    Lihat Detail
</button>
                    </div>

                    {{-- Status Pesanan --}}
                    <div class="mt-4 flex items-center gap-2">
                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <p class="text-[10px] font-bold text-emerald-500 italic tracking-widest">Sedang diproses</p>
                    </div>
                </div>

                {{-- Action Button (Kanan) --}}
                <div class="flex-shrink-0 w-full md:w-auto">
                    <a href="https://wa.me/628123456789" target="_blank"
                        class="flex items-center justify-center gap-2 border border-gray-200 px-6 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all w-full md:w-auto">
                        <i class="fa-brands fa-whatsapp text-sm text-emerald-500"></i>
                        Kontak Penjual
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-20 bg-white border border-dashed border-gray-200 rounded-xl">
                <p class="text-gray-400 text-[10px] tracking-[0.3em]">Belum ada data pesanan untuk dilacak</p>
            </div>
        @endif
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-12 text-center">
        <a href="/katalog" class="bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold tracking-[0.2em] shadow-lg hover:bg-gray-800 transition-all inline-flex items-center gap-3">
            <i class="fa-solid fa-bag-shopping"></i>
            Mulai Belanja
        </a>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modal-detail" 
    class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col md:flex-row">
        <button onclick="tutupModalDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-black z-10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        {{-- Bagian Foto --}}
        <div class="w-full md:w-1/2 bg-gray-50 aspect-square flex items-center justify-center overflow-hidden">
            <img id="detail-foto" src="" class="w-full h-full object-cover" alt="Foto">
        </div>

        {{-- Bagian Teks --}}
        <div class="p-8 md:w-1/2 flex flex-col justify-center text-left">
            <h2 id="detail-nama" class="text-lg font-bold text-[#001f3f] tracking-widest uppercase mb-1">Nama</h2>
            <p id="detail-harga" class="text-xs font-bold text-gray-400 mb-4 italic">IDR 0</p>
            <p id="detail-deskripsi" class="text-[11px] text-gray-500 leading-relaxed mb-6">Deskripsi...</p>
            
            {{-- Info Detail Pesanan --}}
            <div class="pt-4 border-t border-gray-100 flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Jumlah</span>
                    <span id="detail-jumlah" class="text-[10px] font-bold text-[#001f3f]">0 Pcs</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Total Bayar</span>
                    <span id="detail-total" class="text-[10px] font-bold text-[#001f3f]">Rp 0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-lihat-detail').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            
            // Isi elemen modal dengan data dari tombol
            document.getElementById('detail-nama').innerText = data.nama;
            document.getElementById('detail-harga').innerText = 'IDR ' + data.total;
            document.getElementById('detail-deskripsi').innerText = data.deskripsi;
            document.getElementById('detail-foto').src = data.foto;
            
            // Isi data rincian pesanan
            document.getElementById('detail-jumlah').innerText = data.jumlah + ' Pcs';
            document.getElementById('detail-total').innerText = 'Rp ' + data.total;
            
            // Tampilkan modal
            document.getElementById('modal-detail').classList.remove('hidden');
        });
    });

    function tutupModalDetail() {
        document.getElementById('modal-detail').classList.add('hidden');
    }
</script>
@endsection