@extends('layouts.pembeli')
@section('content')
@php
    $items = session('cart') ?? session('pesanan_terakhir') ?? [];
    $total = 0;
    
    foreach($items as $item) {
        $total += $item['harga'] * $item['quantity'];
    }

    $ongkir = session('ongkir', 0);
    $totalPlusOngkir = $total + $ongkir;
@endphp
<div class="py-12 flex justify-center items-center">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-12">
        <div class="text-center mb-10">
            <h1 class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mb-2">Transaksi</h1>
            <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pembayaran</p>
            <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        </div>

        <div class="space-y-6">
            {{-- Total --}}
            <div class="bg-[#F3F5F1] rounded-lg px-6 py-5 flex justify-between items-center">
                <span class="text-[11px] uppercase tracking-[0.2em] text-gray-400">Total Pembayaran</span>
                <span class="text-lg font-bold text-[#001f3f]">Rp {{ number_format($totalPlusOngkir, 0, ',', '.') }}</span>
            </div>

            {{-- SECTION TRANSFER BANK --}}
            <div id="section-transfer" class="border border-gray-100 rounded-lg px-6 py-5 hidden">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">Transfer ke Rekening</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-[#001f3f] text-sm tracking-wide">Hafizh Abdul Halim</p>
                        <p class="text-sm text-gray-400 mt-0.5">Bank BCA &nbsp;·&nbsp; 719-708-5353</p>
                    </div>
                    <div class="bg-[#F3F5F1] rounded-lg px-3 py-1.5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">BCA</span>
                    </div>
                </div>
            </div>

            {{-- SECTION QRIS --}}
            <div id="section-qris" class="border border-gray-100 rounded-lg px-6 py-5 hidden">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-4">Scan QRIS</p>
                <div class="flex flex-col items-center gap-3">
                    <img src="{{ asset('images/qris.png') }}" class="w-120 h-120 object-contain rounded-lg" alt="qris code">
                </div>
                <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                    Scan QR code di atas sesuai<br>
                    total nominal transaksi yang sudah ditentukan.
                </p>
                <div class="flex flex-wrap justify-center gap-2 mt-1">
                    <span class="text-[9px] bg-gray-50 border border-gray-100 px-2 py-1 rounded font-bold text-gray-400 uppercase tracking-widest">GoPay</span>
                    <span class="text-[9px] bg-gray-50 border border-gray-100 px-2 py-1 rounded font-bold text-gray-400 uppercase tracking-widest">OVO</span>
                    <span class="text-[9px] bg-gray-50 border border-gray-100 px-2 py-1 rounded font-bold text-gray-400 uppercase tracking-widest">Dana</span>
                    <span class="text-[9px] bg-gray-50 border border-gray-100 px-2 py-1 rounded font-bold text-gray-400 uppercase tracking-widest">ShopeePay</span>
                    <span class="text-[9px] bg-gray-50 border border-gray-100 px-2 py-1 rounded font-bold text-gray-400 uppercase tracking-widest">M-Banking</span>
                </div>
            </div>

            {{-- Upload Bukti (Form dengan Validasi Interaktif) --}}
            <form id="form-pembayaran" action="{{ route('bukti.upload') }}" method="POST" enctype="multipart/form-data" onsubmit="return validasiBuktiTransfer(event)">
                @csrf
                <input type="hidden" name="id_pesanan" value="{{ session('pesanan_info.id_pesanan') }}">

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Bukti Pembayaran <span class="text-red-400">*</span></label>
                    <label id="dropzone-label" class="flex flex-col items-center justify-center w-full border-2 border-dashed @error('bukti_pembayaran') border-red-400 bg-red-50/10 @enderror border-gray-200 rounded-lg py-8 cursor-pointer hover:border-[#001f3f] hover:bg-[#F3F5F1] transition-all duration-150">
                        <i class="fa-regular fa-image text-2xl text-gray-300 mb-2" id="upload-icon"></i>
                        <span id="upload-text" class="text-[11px] uppercase tracking-widest text-gray-400 text-center px-4">Klik untuk upload bukti pembayaran</span>
                        <span class="text-[10px] text-gray-300 mt-1">PNG, JPG hingga 5MB</span>
                        <input type="file" name="bukti_pembayaran" class="hidden" id="input-bukti" onchange="updateUploadLabel(this)" accept="image/*">
                    </label>
                    
                    {{-- Pesan Error Validasi Khas Desain Kelompokmu --}}
                    <p id="err-bukti" class="hidden text-[9px] text-red-400 mt-2 uppercase tracking-widest font-medium">⚠️ Wajib mengunggah gambar bukti transaksi untuk menyelesaikan pesanan.</p>
                    @error('bukti_pembayaran')
                        <p class="text-[9px] text-red-400 mt-2 uppercase tracking-widest font-medium">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="mt-6 block w-full text-center bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.25em] text-[11px] py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                    Selesai & Kirim Bukti
                </button>
            </form>

        </div>
    </div>
</div>

<script>
    function updateUploadLabel(input) {
        const uploadText = document.getElementById('upload-text');
        const errBukti = document.getElementById('err-bukti');
        const dropzoneLabel = document.getElementById('dropzone-label');
        const uploadIcon = document.getElementById('upload-icon');

        if (input.files && input.files[0]) {
            // Ubah teks box menjadi nama file gambar yang dipilih pembeli
            uploadText.innerText = input.files[0].name;
            
            // Bersihkan indikator error karena file sudah masuk
            errBukti.classList.add('hidden');
            dropzoneLabel.classList.remove('border-red-400', 'bg-red-50/10');
            dropzoneLabel.classList.add('border-gray-200');
            
            // Ubah warna ikon biar estetik menandakan file ada
            uploadIcon.classList.remove('text-gray-300');
            uploadIcon.classList.add('text-[#001f3f]');
        }
    }

    function validasiBuktiTransfer(event) {
        const inputBukti = document.getElementById('input-bukti');
        const errBukti = document.getElementById('err-bukti');
        const dropzoneLabel = document.getElementById('dropzone-label');

        // Jika value kosong (pembeli belum milih gambar sama sekali)
        if (!inputBukti.value) {
            event.preventDefault(); // Gagalkan submit form backend
            
            // Tampilkan notifikasi tulisan merah & ubah border putus-putusnya jadi merah
            errBukti.classList.remove('hidden');
            dropzoneLabel.classList.remove('border-gray-200');
            dropzoneLabel.classList.add('border-red-400', 'bg-red-50/10');
            
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const metode = sessionStorage.getItem('metodePembayaran');

        const sectionTransfer = document.getElementById('section-transfer');
        const sectionQris     = document.getElementById('section-qris');

        if (metode === 'transfer') {
            sectionTransfer.classList.remove('hidden');
            sectionQris.classList.add('hidden');
        } else if (metode === 'qris') {
            sectionQris.classList.remove('hidden');
            sectionTransfer.classList.add('hidden');
        } else {
            sectionTransfer.classList.remove('hidden');
        }
    });
</script>
@endsection