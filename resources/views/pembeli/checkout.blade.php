@extends('layouts.pembeli')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 lg:px-0">
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Konfirmasi Pesanan</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Checkout</h1>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    <a href="/keranjang" class="mb-10 text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-all font-bold flex items-center gap-2 group w-fit">
        <i class="fa-solid fa-arrow-left-long transition-transform group-hover:-translate-x-1"></i> Kembali ke Keranjang
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white border border-gray-100 p-8 shadow-sm rounded-sm">
                <h2 class="font-bold text-[11px] mb-8 uppercase tracking-[0.3em] text-[#001f3f] border-b border-gray-50 pb-4">
                    Data Pengiriman
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Nama Penerima</label>
                        <input type="text" placeholder="Masukkan nama lengkap" class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Nomor Telepon</label>
                        <input type="text" placeholder="08xxxx" class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Email</label>
                        <input type="email" placeholder="email@contoh.com" class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Alamat Lengkap</label>
                        <textarea class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium resize-none" rows="2" placeholder="Nama jalan, nomor rumah, kec/kel"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Kota Tujuan</label>
                        <select id="select-kota" onchange="updateOngkir()" class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer">
                            <option value="0" selected disabled>Pilih Kota</option>
                            <option value="15000">Batam (Rp 15.000)</option>
                            <option value="25000">Jakarta (Rp 25.000)</option>
                            <option value="32000">Yogyakarta (Rp 32.000)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 mb-1 font-bold">Metode Pembayaran</label>
                        <select class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[11px] py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer">
                            <option value="qris">QRIS / E-Wallet</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 p-8 h-fit shadow-sm rounded-sm">
            <div class="flex justify-between items-center mb-8 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-[11px] uppercase tracking-[0.3em] text-[#001f3f]">Total Pembelian</h3>
                <button onclick="toggleDetail()" class="text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-black">
                    <span id="text-detail">Lihat Detail</span>
                </button>
            </div>
            
            <div id="detail-produk" class="hidden space-y-4 mb-8 animate-fadeIn">
                @php $subtotal = 0; @endphp
                @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                        @php $subtotal += $details['harga'] * $details['quantity'] @endphp
                        <div class="flex gap-4 items-center group border-b border-gray-50 pb-4">
                            <div class="w-12 h-12 bg-gray-50 border border-gray-100 flex-shrink-0 overflow-hidden">
                                <img src="{{ $details['foto'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                            </div>
                            <div class="flex-grow">
                                <p class="text-[10px] font-bold text-gray-800 uppercase tracking-widest leading-tight">{{ $details['nama'] }}</p>
                                <p class="text-[9px] text-gray-400 mt-1 font-medium">{{ $details['quantity'] }} x Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[10px] font-bold text-[#001f3f]">Rp {{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="space-y-3">
                <div class="flex justify-between text-[10px] uppercase tracking-widest">
                    <span class="text-gray-400 font-medium">Subtotal Produk</span>
                    <span id="subtotal-produk" class="font-bold text-gray-800" data-harga="{{ $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[10px] uppercase tracking-widest">
                    <span class="text-gray-400 font-medium">Ongkos Kirim</span>
                    <span id="ongkir-display" class="font-bold text-gray-800">Rp 0</span>
                </div>
                <div class="flex flex-col gap-1 pt-6 border-t border-gray-100">
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400">Total Pembayaran</span>
                    <span id="total-bayar" class="text-xl font-bold text-[#001f3f]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="/pembayaran" class="mt-8 block w-full text-center bg-[#001f3f] text-white py-4 rounded-full font-bold text-[10px] hover:bg-gray-800 shadow-md hover:shadow-xl transition-all uppercase tracking-[0.2em]">
                LANJUT KE PEMBAYARAN
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.4s ease forwards; }
</style>

<script>
    function toggleDetail() {
        const detail = document.getElementById('detail-produk');
        const text = document.getElementById('text-detail');
        if (detail.classList.contains('hidden')) {
            detail.classList.remove('hidden');
            text.innerText = 'Sembunyikan';
        } else {
            detail.classList.add('hidden');
            text.innerText = 'Lihat Detail';
        }
    }

    function updateOngkir() {
        const select = document.getElementById('select-kota');
        const ongkirDisplay = document.getElementById('ongkir-display');
        const totalDisplay = document.getElementById('total-bayar');
        const subtotal = parseInt(document.getElementById('subtotal-produk').getAttribute('data-harga'));
        const ongkirBaru = parseInt(select.value);
        const totalBaru = subtotal + ongkirBaru;
        ongkirDisplay.innerText = 'Rp ' + ongkirBaru.toLocaleString('id-ID');
        totalDisplay.innerText = 'Rp ' + totalBaru.toLocaleString('id-ID');
    }
</script>
@endsection