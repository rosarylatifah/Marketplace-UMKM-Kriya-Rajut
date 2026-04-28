@extends('layouts.pembeli')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 lg:px-0">
    {{-- Header Halaman --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Tas Anda</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Keranjang Belanja</h1>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- List Produk --}}
        <div class="lg:col-span-2 space-y-4">
            
            @foreach(session('cart') as $id => $details)
            {{-- Item Card --}}
            <div id="item-{{ $id }}" class="product-card p-4 flex gap-4 bg-white border border-gray-100 shadow-sm relative transition-all group">
                <div class="w-20 h-20 bg-white border border-gray-50 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="{{ $details['foto'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" alt="{{ $details['nama'] }}">
                </div>
                
                <div class="flex-grow flex flex-col justify-between py-0.5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-[12px] text-gray-800 uppercase tracking-widest">{{ $details['nama'] }}</h3>
                            <p class="text-[10px] harga-satuan text-gray-400 mt-0.5 font-medium" data-harga="{{ $details['harga'] }}">
                                IDR {{ number_format($details['harga'], 0, ',', '.') }}
                            </p>
                            <button onclick="bukaModalDetail('{{ $details['nama'] }}', '{{ number_format($details['harga'], 0, ',', '.') }}', 'Produk pilihan dari koleksi kami.', 'Tersedia')" class="mt-2 text-[9px] uppercase tracking-widest font-bold text-[#001f3f] hover:underline">
                                Lihat Detail
                            </button>
                        </div>
                        
                        <button onclick="setProdukYangMauDihapus('{{ $id }}')" class="text-gray-300 hover:text-red-400 transition-colors">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <div class="flex flex-col items-end">
                        <div class="flex items-center border border-gray-200 rounded-full overflow-hidden h-7 mb-1">
                            <button onclick="updateQty('{{ $id }}', -1)" class="w-7 flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-[10px] font-bold border-r border-gray-200">-</button>
                            <span class="qty-produk px-3 bg-white text-[10px] font-bold text-gray-700">{{ $details['quantity'] }}</span>
                            <button onclick="updateQty('{{ $id }}', 1)" class="w-7 flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-[10px] font-bold border-l border-gray-200">+</button>
                        </div>
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest">
                            Subtotal <span class="font-bold text-[#001f3f] text-[11px] ml-1">Rp<span class="subtotal-item">{{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</span></span>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Section Checkout --}}
        <div class="bg-white border border-gray-100 p-8 h-fit shadow-sm rounded-sm">
            <h3 class="font-bold text-[11px] mb-8 uppercase tracking-[0.3em] text-[#001f3f] border-b border-gray-50 pb-4">Ringkasan Pesanan</h3>
            
            <div class="space-y-4 mb-4">
                <div class="flex justify-between text-[11px] uppercase tracking-widest">
                    <span class="text-gray-400">Total Harga</span>
                    <span id="total-harga-produk" class="font-bold text-gray-800">Rp 0</span>
                </div>
                <div class="flex justify-between text-[11px] uppercase tracking-widest">
                    <span class="text-gray-400">Jumlah Item</span>
                    <span id="total-qty-item" class="font-bold text-gray-800">0 Produk</span>
                </div>
                <div class="flex justify-between text-[11px] uppercase tracking-widest pt-4 border-t border-gray-50">
                    <span class="text-gray-800 font-bold">Estimasi Total</span>
                    <span id="estimasi-total" class="font-bold text-[#001f3f] text-sm">Rp 0</span>
                </div>
            </div>

            <p class="text-[9px] text-gray-400 italic mb-8">* Estimasi total belum termasuk biaya pengiriman (ongkir).</p>
            
            <a href="/checkout" class="block w-full text-center bg-[#001f3f] text-white py-4 rounded-full font-bold text-[10px] hover:bg-gray-800 shadow-md hover:shadow-xl transition-all uppercase tracking-[0.2em]">
                BUAT PESANAN SEKARANG
            </a>
        </div>
    </div>
    @else
    {{-- Tampilan Keranjang Kosong --}}
    <div class="text-center py-20 border border-dashed border-gray-200">
        <p class="text-gray-400 text-[11px] uppercase tracking-widest mb-6">Keranjang Anda masih kosong</p>
        <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest">Mulai Belanja</a>
    </div>
    @endif
</div>

{{-- MODAL DETAIL --}}
<div id="modal-detail" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col md:flex-row">
        <button onclick="tutupModalDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-black z-10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="w-full md:w-1/2 bg-gray-50 aspect-square flex items-center justify-center italic text-gray-300 text-[10px]">Preview Image</div>
        <div class="p-8 md:w-1/2 flex flex-col justify-center">
            <h2 id="detail-nama" class="text-lg font-bold text-[#001f3f] uppercase tracking-widest mb-1">Nama Produk</h2>
            <p id="detail-harga" class="text-xs font-bold text-gray-400 mb-4">Rp 0</p>
            <p id="detail-deskripsi" class="text-[11px] text-gray-500 leading-relaxed mb-6">Deskripsi...</p>
            <div class="pt-4 border-t border-gray-100">
                <span id="detail-stok" class="text-[9px] bg-gray-50 px-3 py-1 text-gray-600 font-bold uppercase">Stok: Tersedia</span>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="popup-hapus" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white p-8 max-w-sm w-full text-center shadow-2xl border border-gray-100">
        <h3 class="font-bold text-xs uppercase tracking-widest mb-2">Hapus Produk?</h3>
        <p class="text-[10px] text-gray-500 mb-6 uppercase">Yakin ingin menghapus item ini dari keranjang?</p>
        <div class="flex gap-4">
            <button onclick="tutupModalHapus()" class="flex-1 text-[10px] font-bold uppercase text-gray-400">Batal</button>
            <button id="btn-konfirmasi-hapus" class="flex-1 bg-red-600 text-white py-3 rounded-full text-[10px] font-bold uppercase shadow-lg">Hapus</button>
        </div>
    </div>
</div>

<script>
    let idYangMauDihapus = null;

    document.addEventListener('DOMContentLoaded', () => hitungTotalSemua());

    function bukaModalDetail(nama, harga, deskripsi, stok) {
        document.getElementById('detail-nama').innerText = nama;
        document.getElementById('detail-harga').innerText = 'Rp ' + harga;
        document.getElementById('detail-deskripsi').innerText = deskripsi;
        document.getElementById('detail-stok').innerText = stok;
        document.getElementById('modal-detail').classList.remove('hidden');
    }

    function tutupModalDetail() {
        document.getElementById('modal-detail').classList.add('hidden');
    }

    function setProdukYangMauDihapus(id) {
        idYangMauDihapus = id;
        document.getElementById('popup-hapus').classList.remove('hidden');
    }

    function tutupModalHapus() {
        document.getElementById('popup-hapus').classList.add('hidden');
    }

    document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function() {
        if (idYangMauDihapus) {
            // Sini nanti ditambahin logic AJAX buat hapus di session Laravel
            document.getElementById('item-' + idYangMauDihapus).remove();
            hitungTotalSemua();
            tutupModalHapus();
            
            // Cek jika sudah kosong
            if(document.querySelectorAll('.product-card').length === 0) {
                location.reload(); // Reload buat nampilin pesan "Keranjang Kosong"
            }
        }
    });

    function updateQty(id, perubahan) {
        const card = document.getElementById('item-' + id);
        const qtyElement = card.querySelector('.qty-produk');
        const subtotalElement = card.querySelector('.subtotal-item');
        const hargaSatuan = parseInt(card.querySelector('.harga-satuan').getAttribute('data-harga'));

        let qtySekarang = parseInt(qtyElement.innerText) + perubahan;
        if (qtySekarang < 1) qtySekarang = 1;

        qtyElement.innerText = qtySekarang;
        subtotalElement.innerText = (hargaSatuan * qtySekarang).toLocaleString('id-ID');
        hitungTotalSemua();
    }

    function hitungTotalSemua() {
        let totalHarga = 0;
        let totalQty = 0;
        document.querySelectorAll('.product-card').forEach(card => {
            totalQty += parseInt(card.querySelector('.qty-produk').innerText);
            // Hapus titik ribuan sebelum diconvert ke integer
            const subtotalText = card.querySelector('.subtotal-item').innerText.replace(/\./g, '');
            totalHarga += parseInt(subtotalText);
        });
        document.getElementById('total-harga-produk').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('total-qty-item').innerText = totalQty + ' Produk';
        document.getElementById('estimasi-total').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
    }
</script>
@endsection