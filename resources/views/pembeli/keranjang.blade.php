@extends('layouts.pembeli')

@section('content')
<div class="py-10">
    <h1 class="text-2xl font-bold mb-8">Keranjang Belanja</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            
            <div id="produk-1" class="poduct-card border border-gray-400 p-6 flex gap-6 bg-white relative">
                <div class="w-28 h-28 bg-gray-200 border border-gray-300 flex-shrink-0 flex items-center justify-center text-center px-2">
                    <span class="text-sm font-medium text-gray-800">Foto Produk</span>
                </div>
                
                <div class="flex-grow">
                    <h3 class="font-bold text-xl text-gray-800">Tas Rajut Boboho</h3>
                    <p class="text-lg harga-satuan text-gray-800 mt-1" data-harga="150000">Rp. 150.000</p>
                    
                    <button data-modal-target="popup-hapus" data-modal-toggle="popup-hapus" onclick="setProdukYangMauDihapus('produk-1')" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-trash-can text-lg"></i>
                    </button>

                    <div class="absolute bottom-6 right-6 flex flex-col items-end">
                        <div class="flex items-center border border-gray-400">
                            <button onclick="updateQty('produk-1', -1)" class="px-3 py-1 bg-gray-200 border-r border-gray-400 hover:bg-gray-300">&lt;</button>
                            <span class="qty-produk px-5 py-1 bg-white font-medium">1</span>
                            <button onclick="updateQty('produk-1', 1)" class="px-3 py-1 bg-gray-200 border-l border-gray-400 hover:bg-gray-300">&gt;</button>
                        </div>
                        <p class="text-[11px] mt-2 italic text-gray-500">Subtotal: Rp<span class="subtotal-item">150.000</span></p>
                    </div>
                </div>
            </div>

            <div id="produk-2" class="product-card border border-gray-400 p-6 flex gap-6 bg-white relative">
                <div class="w-28 h-28 bg-gray-200 border border-gray-300 flex-shrink-0 flex items-center justify-center text-center px-2">
                    <span class="text-sm font-medium text-gray-800">Foto Produk</span>
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold text-xl text-gray-800">Dompet Mini Rajut</h3>
                    <p class="text-lg text-gray-800 mt-1 harga-satuan" data-harga="65000">Rp. 65.000</p>
                    
                    <button data-modal-target="popup-hapus" data-modal-toggle="popup-hapus" onclick="setProdukYangMauDihapus('produk-2')" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-trash-can text-lg"></i>
                    </button>

                    <div class="absolute bottom-6 right-6 flex flex-col items-end">
                        <div class="flex items-center border border-gray-400">
                            <button onclick="updateQty('produk-2', -1)" class="px-3 py-1 bg-gray-200 border-r border-gray-400 hover:bg-gray-300">&lt;</button>
                            <span class="qty-produk px-5 py-1 bg-white font-medium">2</span>
                            <button onclick="updateQty('produk-2', 1)" class="px-3 py-1 bg-gray-200 border-l border-gray-400 hover:bg-gray-300">&gt;</button>
                        </div>
                        <p class="text-[11px] mt-2 italic text-gray-500">Subtotal: Rp<span class="subtotal-item">Rp130.000</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-gray-400 p-8 bg-white h-fit">
            <h3 class="font-bold text-lg mb-6">Total (Tidak Termasuk Ongkir)</h3>
            <div class="flex justify-between text-base mb-3">
                <span class="text-gray-600">Subtotal</span>
                <span id="total-akhir" class="font-medium">Rp 280.000</span>
            </div>
            <div class="flex justify-between text-base mb-6">
                <span class="text-gray-600">Total Produk</span>
                <span class="font-medium">2 produk</span>
            </div>
            <p class="text-[11px] text-gray-500 italic mb-8">*Ongkos kirim akan dihitung pada halaman checkout</p>
            <a href="/checkout" class="block w-full text-center bg-gray-300 py-3 border border-gray-400 font-bold text-base hover:bg-gray-400 transition-colors uppercase tracking-wider">Checkout</a>
        </div>
    </div>
</div>

<div id="popup-hapus" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white border-2 border-gray-400 shadow">
            <div class="p-6 text-center">
                <h3 class="mb-5 text-lg font-bold text-gray-800 uppercase">Hapus Produk?</h3>
                <div class="flex justify-center gap-4">
                    <button id="btn-konfirmasi-hapus" data-modal-hide="popup-hapus" type="button" class="text-white bg-red-600 hover:bg-red-800 font-bold px-10 py-2 border border-gray-400">
                        IYA
                    </button>
                    <button data-modal-hide="popup-hapus" type="button" class="text-gray-800 bg-gray-200 hover:bg-gray-300 font-bold px-10 py-2 border border-gray-400">
                        TIDAK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let idYangMauDihapus = null;

    function setProdukYangMauDihapus(id) {
        idYangMauDihapus = id;
    }

    document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function() {
        if (idYangMauDihapus) {
            const elemen = document.getElementById(idYangMauDihapus);
            if (elemen) {
                elemen.remove();
                // Update total akhir setelah barang dihapus
                hitungTotalSemua();
            }
        }
    });

    function updateQty(idProduk, perubahan) {
        const produk = document.getElementById(idProduk);
        const qtyElement = produk.querySelector('.qty-produk');
        const subtotalElement = produk.querySelector('.subtotal-item');
        const hargaSatuan = parseInt(produk.querySelector('.harga-satuan').getAttribute('data-harga'));

        let qtySekarang = parseInt(qtyElement.innerText);
        qtySekarang += perubahan;

        if (qtySekarang < 1) qtySekarang = 1;

        qtyElement.innerText = qtySekarang;

        // Hitung subtotal baru
        const subtotal = hargaSatuan * qtySekarang;
        // Tampilkan dengan format titik ribuan Indonesia
        subtotalElement.innerText = subtotal.toLocaleString('id-ID');

        hitungTotalSemua();
    }

    function hitungTotalSemua() {
        let totalSemua = 0;
        const semuaSubtotal = document.querySelectorAll('.subtotal-item');
        
        semuaSubtotal.forEach(el => {
            // Bersihkan titik dan ambil angkanya saja
            const nilai = parseInt(el.innerText.replace(/\./g, '')) || 0;
            totalSemua += nilai;
        });

        const totalAkhirElement = document.getElementById('total-akhir');
        if (totalAkhirElement) {
            totalAkhirElement.innerText = 'Rp ' + totalSemua.toLocaleString('id-ID');
        }
    }
</script>
@endsection