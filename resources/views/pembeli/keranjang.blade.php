@extends('layouts.pembeli')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 lg:px-0">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[11px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Tas Anda</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Keranjang Belanja</h1>
            <div class="mt-3 h-px w-12 bg-[#001f3f]"></div>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- List Produk --}}
        <div class="lg:col-span-2 space-y-3">
            @foreach(session('cart') as $id => $details)
            <div id="item-{{ $id }}"
                class="product-card p-4 flex gap-4 bg-white border border-gray-100 shadow-sm relative transition-all group">
                <div class="w-20 h-20 bg-white border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="{{ asset('images/' . $details['foto']) }}" class="w-full h-full object-cover" alt="{{ $details['nama'] }}">
                </div>
                <div class="flex-grow flex flex-col justify-between py-0.5 min-w-0">
                    <div class="flex justify-between items-start gap-2">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-[#001f3f] uppercase tracking-wide leading-snug line-clamp-2">{{ $details['nama'] }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5 font-medium harga-satuan" data-harga="{{ $details['harga'] }}">
                                IDR {{ number_format($details['harga'], 0, ',', '.') }}
                            </p>
                            <button
                                class="btn-lihat-detail mt-1 text-[11px] tracking-widest font-bold text-[#001f3f] hover:underline block text-left"
                                data-nama="{{ $details['nama'] }}"
                                data-harga="{{ number_format($details['harga'], 0, ',', '.') }}"
                                data-deskripsi="{{ $details['deskripsi'] ?? 'Tidak ada deskripsi.' }}"
                                data-stok="Tersedia"
                                data-foto="{{ asset('images/' . $details['foto']) }}">
                                Lihat Detail
                            </button>
                        </div>
                        <button onclick="setProdukYangMauDihapus('{{ $id }}')" class="text-gray-300 hover:text-red-400 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                    <div class="flex flex-col items-end mt-2">
                        <div class="flex items-center border border-gray-200 rounded-full overflow-hidden h-7 mb-1">
                            <button onclick="updateQty('{{ $id }}', -1)"
                                class="w-7 flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-xs font-bold border-r border-gray-200">-</button>
                            <span class="qty-produk px-3 bg-white text-xs font-bold text-gray-700">{{ $details['quantity'] }}</span>
                            <button onclick="updateQty('{{ $id }}', 1)"
                                class="w-7 flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-xs font-bold border-l border-gray-200">+</button>
                        </div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">
                            Subtotal <span class="font-bold text-[#001f3f] text-xs ml-1">Rp<span class="subtotal-item">{{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</span></span>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan --}}
        <div class="bg-white border border-gray-100 p-6 h-fit shadow-sm rounded-sm">
            <h3 class="font-bold text-xs mb-6 uppercase tracking-[0.3em] text-[#001f3f] border-b border-gray-50 pb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-xs uppercase tracking-widest">
                    <span class="text-gray-500">Total Harga</span>
                    <span id="total-harga-produk" class="font-bold text-gray-800">Rp 0</span>
                </div>
                <div class="flex justify-between text-xs uppercase tracking-widest">
                    <span class="text-gray-500">Jumlah Item</span>
                    <span id="total-qty-item" class="font-bold text-gray-800">0 Produk</span>
                </div>
                <div class="flex justify-between text-xs uppercase tracking-widest pt-4 border-t border-gray-50">
                    <span class="text-gray-800 font-bold">Estimasi Total</span>
                    <span id="estimasi-total" class="font-bold text-[#001f3f] text-sm">Rp 0</span>
                </div>
            </div>
            <p class="text-[12px] text-gray-400 italic mb-6">* Estimasi total belum termasuk ongkos kirim.</p>
            <a href="/checkout"
                class="block w-full text-center bg-[#001f3f] text-white py-4 rounded-full font-bold text-xs hover:bg-gray-800 shadow-md hover:shadow-xl transition-all uppercase tracking-[0.2em]">
                BUAT PESANAN SEKARANG
            </a>
        </div>
    </div>

    @else
    <div class="text-center py-20">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-6">Belum ada produk di keranjang ini</p>
        <a href="/katalog"
            class="inline-block bg-[#001f3f] text-white px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-800 shadow-md hover:shadow-xl transition-all">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

{{-- MODAL DETAIL --}}
<div id="modal-detail" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col md:flex-row">
        <button onclick="tutupModalDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-black z-10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="w-full md:w-1/2 bg-gray-50 aspect-square flex items-center justify-center overflow-hidden">
            <img id="detail-foto" src="" class="w-full h-full object-cover" alt="Foto Produk">
        </div>
        <div class="p-8 md:w-1/2 flex flex-col justify-center">
            <h2 id="detail-nama" class="text-lg font-bold text-[#001f3f] uppercase tracking-wide mb-2">Nama Produk</h2>
            <p id="detail-harga" class="text-sm font-bold text-gray-400 mb-4">Rp 0</p>
            <p id="detail-deskripsi" class="text-xs text-gray-500 leading-relaxed mb-6">Deskripsi...</p>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="popup-hapus" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white p-8 max-w-sm w-full text-center shadow-2xl border border-gray-100">
        <h3 class="font-bold text-sm uppercase tracking-widest mb-2">Hapus Produk?</h3>
        <p class="text-xs text-gray-500 mb-6 uppercase">Yakin ingin menghapus item ini dari keranjang?</p>
        <div class="flex gap-4">
            <button onclick="tutupModalHapus()" class="flex-1 text-xs font-bold uppercase text-gray-400">Batal</button>
            <button id="btn-konfirmasi-hapus"
                class="flex-1 bg-red-600 text-white py-3 rounded-full text-xs font-bold uppercase shadow-lg">Hapus</button>
        </div>
    </div>
</div>

<script>
    let idYangMauDihapus = null;

    document.addEventListener('DOMContentLoaded', () => {
        hitungTotalSemua();
        document.querySelectorAll('.btn-lihat-detail').forEach(function(btn) {
            btn.addEventListener('click', function() {
                bukaModalDetail(this.dataset.nama, this.dataset.harga, this.dataset.deskripsi, this.dataset.stok, this.dataset.foto);
            });
        });
    });

    function setProdukYangMauDihapus(id) {
        idYangMauDihapus = id;
        document.getElementById('popup-hapus').classList.remove('hidden');
    }
    function tutupModalHapus() { document.getElementById('popup-hapus').classList.add('hidden'); }

    document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function () {
        if (idYangMauDihapus) {
            fetch("{{ route('cart.remove') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id: idYangMauDihapus })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    document.getElementById('item-' + idYangMauDihapus).remove();
                    hitungTotalSemua();
                    tutupModalHapus();
                    if (document.querySelectorAll('.product-card').length === 0) location.reload();
                }
            });
        }
    });

    function updateQty(id, perubahan) {
        const card = document.getElementById('item-' + id);
        const qtyElement = card.querySelector('.qty-produk');
        const subtotalElement = card.querySelector('.subtotal-item');
        const hargaSatuan = parseInt(card.querySelector('.harga-satuan').getAttribute('data-harga'));
        let qtyBaru = parseInt(qtyElement.innerText) + perubahan;
        if (qtyBaru < 1) return;
        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ id: id, quantity: qtyBaru })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                qtyElement.innerText = qtyBaru;
                subtotalElement.innerText = (hargaSatuan * qtyBaru).toLocaleString('id-ID');
                hitungTotalSemua();
            }
        });
    }

    function hitungTotalSemua() {
        let totalHarga = 0, totalQty = 0;
        document.querySelectorAll('.product-card').forEach(card => {
            totalQty += parseInt(card.querySelector('.qty-produk').innerText);
            totalHarga += parseInt(card.querySelector('.subtotal-item').innerText.replace(/\./g, ''));
        });
        document.getElementById('total-harga-produk').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('total-qty-item').innerText = totalQty + ' Produk';
        document.getElementById('estimasi-total').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
    }

    function bukaModalDetail(nama, harga, deskripsi, stok, foto) {
        document.getElementById('detail-nama').innerText = nama;
        document.getElementById('detail-harga').innerText = 'IDR ' + harga;
        document.getElementById('detail-deskripsi').innerText = deskripsi;
        document.getElementById('detail-foto').src = foto;
        document.getElementById('modal-detail').classList.remove('hidden');
    }
    function tutupModalDetail() { document.getElementById('modal-detail').classList.add('hidden'); }
</script>
@endsection
