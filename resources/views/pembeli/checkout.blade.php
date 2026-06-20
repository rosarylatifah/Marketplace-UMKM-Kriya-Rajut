@extends('layouts.pembeli')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 lg:px-0">
    <div class="mb-5 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[11px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Konfirmasi Pesanan</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Checkout</h1>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    <a href="/keranjang" class="mb-8 text-xs uppercase tracking-[0.2em] text-gray-400 hover:text-[#001f3f] transition-all font-bold flex items-center gap-2 group w-fit">
        <i class="fa-solid fa-arrow-left-long transition-transform group-hover:-translate-x-1"></i> Kembali ke Keranjang
    </a>

    <form id="form-checkout" action="{{ route('checkout.proses') }}" method="POST">
        <input type="hidden" name="ongkir" id="input-ongkir-hidden" value="0">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-100 p-6 sm:p-8 shadow-sm rounded-sm">
                    <h2 class="font-bold text-xs mb-7 uppercase tracking-[0.3em] text-[#001f3f] border-b border-gray-50 pb-4">
                        Data Pengiriman
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Nama Penerima <span class="text-red-400">*</span>
                            </label>
                            <input name="nama" id="input-nama" type="text" placeholder="Masukkan nama lengkap"
                                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-sm py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300">
                            <p id="err-nama" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Nama wajib diisi</p>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Nomor Telepon <span class="text-red-400">*</span>
                            </label>
                            <input name="telepon" id="input-telepon" type="text" placeholder="08xxxx"
                                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-sm py-3 px-0 transition-all duration-300 font-medium">
                            <p id="err-telepon" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Nomor telepon wajib diisi</p>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input name="email" id="input-email" type="email" placeholder="email@contoh.com"
                                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-sm py-3 px-0 transition-all duration-300 font-medium">
                            <p id="err-email" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Email wajib diisi</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Alamat Lengkap <span class="text-red-400">*</span>
                            </label>
                            <textarea name="alamat" id="input-alamat" class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-sm py-3 px-0 transition-all duration-300 font-medium resize-none" rows="2" placeholder="Nama jalan, nomor rumah, kec/kel (Wilayah Batam)"></textarea>
                            <p id="err-alamat" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Alamat wajib diisi</p>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Opsi Pengantaran <span class="text-red-400">*</span>
                            </label>
                            <select name="opsi_pengantaran" id="select-opsi-pengantaran" onchange="updateOngkir()"
                                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-xs py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer">
                                <option value="" selected disabled>Pilih Opsi Pengantaran</option>
                                <option value="kurir_lokal" data-ongkir="10000">Kurir Lokal Batam (Maks 1kg) - Rp 10.000</option>
                                <option value="custom_shipment" data-ongkir="0">GoSend / GrabExpress - Biaya Menyesuaikan</option>
                            </select>
                            <p id="err-opsi" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Opsi pengantaran wajib dipilih</p>

                            <div id="keterangan-opsi-box" class="hidden opacity-0 transform -translate-y-2 transition-all duration-300 mt-4 p-4 bg-gray-50 border border-gray-100 rounded-sm flex items-start gap-3">
                                <div id="keterangan-icon" class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs mt-0.5 font-bold"></div>
                                <div class="flex-1">
                                    <h4 id="keterangan-title" class="text-xs font-bold uppercase tracking-wider text-[#001f3f] mb-1">Mengenai Opsi</h4>
                                    <p id="keterangan-text" class="text-xs text-gray-500 font-medium leading-relaxed"></p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1 font-bold">
                                Metode Pembayaran <span class="text-red-400">*</span>
                            </label>
                            <select name="metode_pembayaran" id="select-pembayaran"
                                class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-xs py-3 px-0 transition-all duration-300 font-bold uppercase tracking-widest cursor-pointer">
                                <option value="" selected disabled>Pilih Metode</option>
                                <option value="qris">QRIS / E-Wallet</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                            <p id="err-pembayaran" class="hidden text-[10px] text-red-400 mt-1 uppercase tracking-widest">Metode pembayaran wajib dipilih</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 p-6 h-fit shadow-sm rounded-sm">
                <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                    <h3 class="font-bold text-xs uppercase tracking-[0.3em] text-[#001f3f]">Total Pembelian</h3>
                    <button type="button" onclick="toggleDetail()" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black">
                        <span id="text-detail">Lihat Detail</span>
                    </button>
                </div>

                <div id="detail-produk" class="hidden space-y-3 mb-6 animate-fadeIn">
                    @php $subtotal = 0; @endphp
                    @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                            @php $subtotal += $details['harga'] * $details['quantity'] @endphp
                            <div class="flex gap-3 items-center group border-b border-gray-50 pb-3">
                                <div class="w-12 h-12 bg-gray-50 border border-gray-100 flex-shrink-0 overflow-hidden">
                                    <img src="{{ $details['foto'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-[11px] font-bold text-gray-800 uppercase tracking-wide leading-tight line-clamp-2">{{ $details['nama'] }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">{{ $details['quantity'] }} × Rp {{ number_format($details['harga'], 0, ',', '.') }}</p>
                                </div>
                                <p class="text-[11px] font-bold text-[#001f3f] flex-shrink-0">Rp {{ number_format($details['harga'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-xs uppercase tracking-widest">
                        <span class="text-gray-400 font-medium">Subtotal Produk</span>
                        <span id="subtotal-produk" class="font-bold text-gray-800" data-harga="{{ $subtotal }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs uppercase tracking-widest">
                        <span class="text-gray-400 font-medium">Ongkos Kirim</span>
                        <span id="ongkir-display" class="font-bold text-gray-800">Rp 0</span>
                    </div>
                    <div id="catatan-ongkir-box" class="hidden bg-gray-50 border border-gray-100 p-3 text-[10px] uppercase tracking-wider text-gray-500 leading-relaxed rounded-sm transition-all duration-300"></div>
                    <div class="flex justify-between pt-5 border-t border-gray-100">
                        <div class="flex flex-col gap-1">
                            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-400">Total Pembayaran</span>
                            <span id="total-bayar" class="text-xl font-bold text-[#001f3f]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="validasiDanLanjut()"
                    class="mt-7 block w-full text-center bg-[#001f3f] text-white py-4 rounded-full font-bold text-xs hover:bg-gray-800 shadow-md hover:shadow-xl transition-all uppercase tracking-[0.2em]">
                    LANJUT KE PEMBAYARAN
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fadeIn { animation: fadeIn 0.4s ease forwards; }
    .input-error { border-color: #f87171 !important; }
</style>

<script>
    function toggleDetail() {
        const detail = document.getElementById('detail-produk');
        const text = document.getElementById('text-detail');
        if (detail.classList.contains('hidden')) {
            detail.classList.remove('hidden'); text.innerText = 'Sembunyikan';
        } else {
            detail.classList.add('hidden'); text.innerText = 'Lihat Detail';
        }
    }

    function updateOngkir() {
        const select = document.getElementById('select-opsi-pengantaran');
        const ongkirDisplay = document.getElementById('ongkir-display');
        const totalDisplay = document.getElementById('total-bayar');
        const catatanBox = document.getElementById('catatan-ongkir-box');
        const infoBox = document.getElementById('keterangan-opsi-box');
        const infoIcon = document.getElementById('keterangan-icon');
        const infoTitle = document.getElementById('keterangan-title');
        const infoText = document.getElementById('keterangan-text');
        const subtotal = parseInt(document.getElementById('subtotal-produk').getAttribute('data-harga'));
        const selectedOption = select.options[select.selectedIndex];
        const ongkirBaru = parseInt(selectedOption.getAttribute('data-ongkir')) || 0;
        const value = select.value;
        const totalBaru = subtotal + ongkirBaru;

        catatanBox.classList.add('hidden');
        infoBox.classList.remove('opacity-100', 'translate-y-0');
        infoBox.classList.add('opacity-0', '-translate-y-2', 'hidden');
        infoIcon.className = "flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs mt-0.5 font-bold";

        if (value === 'kurir_lokal') {
            infoTitle.innerText = "Kurir Lokal Batam (Paket Standar)";
            infoText.innerText = "Berlaku tarif ke seluruh wilayah Batam. Khusus produk dengan ukuran normal (berat maks 1kg) muat di kantong plastik sedang.";
            infoIcon.innerHTML = "i";
            infoIcon.classList.add('bg-[#001f3f]/10', 'text-[#001f3f]');
            catatanBox.innerHTML = "* Pengiriman khusus wilayah kota Batam. Sistem otomatis menambahkan tarif flat kurir lokal.";
            catatanBox.classList.remove('hidden');
            tampilkanInfoBox(infoBox);
        } else if (value === 'custom_shipment') {
            infoTitle.innerText = "GoSend/GrabExpress (Custom Shipment)";
            infoText.innerText = "Untuk total produk berukuran besar/lebar (> 1 kg). Pengiriman via GoSend/GrabExpress. Ongkir dibayar terpisah langsung ke kurir saat pengantaran.";
            infoIcon.innerHTML = "!";
            infoIcon.classList.add('bg-amber-100', 'text-amber-600');
            catatanBox.innerHTML = "* Ongkos kirim riil akan dibayarkan secara terpisah di luar transaksi website ini.";
            catatanBox.classList.remove('hidden');
            tampilkanInfoBox(infoBox);
        }

        ongkirDisplay.innerText = 'Rp ' + ongkirBaru.toLocaleString('id-ID');
        totalDisplay.innerText = 'Rp ' + totalBaru.toLocaleString('id-ID');
        document.getElementById('input-ongkir-hidden').value = ongkirBaru;
    }

    function tampilkanInfoBox(element) {
        element.classList.remove('hidden');
        setTimeout(() => {
            element.classList.remove('opacity-0', '-translate-y-2');
            element.classList.add('opacity-100', 'translate-y-0');
        }, 50);
    }

    function validasiDanLanjut() {
        const fields = [
            { id: 'input-nama', errId: 'err-nama' },
            { id: 'input-telepon', errId: 'err-telepon' },
            { id: 'input-email', errId: 'err-email' },
            { id: 'input-alamat', errId: 'err-alamat' },
            { id: 'select-opsi-pengantaran', errId: 'err-opsi' },
            { id: 'select-pembayaran', errId: 'err-pembayaran' },
        ];
        let valid = true;
        fields.forEach(function(field) {
            const el = document.getElementById(field.id);
            const err = document.getElementById(field.errId);
            if (!el.value.trim()) {
                err.classList.remove('hidden'); el.classList.add('input-error'); valid = false;
            } else {
                err.classList.add('hidden'); el.classList.remove('input-error');
            }
        });
        if (!valid) {
            const firstError = document.querySelector('.input-error');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        sessionStorage.setItem('metodePembayaran', document.getElementById('select-pembayaran').value);
        document.getElementById('form-checkout').submit();
    }
</script>
@endsection
