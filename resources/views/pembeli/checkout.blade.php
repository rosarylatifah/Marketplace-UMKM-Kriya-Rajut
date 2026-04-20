@extends('layouts.pembeli')

@section('content')
<div class="py-10">
    <button onclick="window.history.back()" class="text-gray-800 mb-6 font-medium">&lt; Kembali</button>
    <h1 class="text-2xl font-bold mb-8">Checkout</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 border border-gray-400 p-8 bg-white space-y-6">
            <h2 class="font-bold text-lg">Data Pengiriman</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Nama Penerima</label>
                    <input type="text" value="Budi Sanjaya" class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0">
                </div>
                <div>
                    <label class="block text-sm mb-1">Nomor Telepon</label>
                    <input type="text" value="081234567890" class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0">
                </div>
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" value="budisnjy@gmail.com" class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0">
                </div>
                <div>
                    <label class="block text-sm mb-1">Alamat Pengiriman</label>
                    <textarea class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0" rows="3">Perumahan Kostarica D.23, Jl. Jalan, Kel. Pakuwon Kec. Lembulang</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Kota Tujuan (Ongkir)</label>
                        <select class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0">
                            <option>Yogyakarta (32.000)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Metode Pembayaran</label>
                        <select class="w-full border-gray-400 bg-gray-200 p-2 text-sm focus:ring-0">
                            <option>Transfer Bank</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-gray-400 p-6 bg-white h-fit space-y-6">
            <h3 class="font-bold border-b border-gray-200 pb-2">Total Pembelian</h3>
            <div class="space-y-4">
                <div class="flex gap-3 items-center">
                    <div class="w-12 h-12 bg-gray-200 border border-gray-300 flex-shrink-0 flex items-center justify-center text-[8px] uppercase">Foto</div>
                    <div class="text-xs">
                        <p class="font-bold">Tas Rajut Boboho</p>
                        <p class="text-gray-500">1 x Rp150.000</p>
                    </div>
                </div>
            </div>
            <hr class="border-gray-800">
            <div class="text-sm space-y-2">
                <div class="flex justify-between italic text-gray-600"><span>Subtotal Produk</span><span>Rp280.000</span></div>
                <div class="flex justify-between italic text-gray-600"><span>Ongkos Kirim</span><span>Rp32.000</span></div>
            </div>
            <div class="pt-4">
                <p class="text-xs font-bold uppercase mb-1">Total Pembayaran</p>
                <p class="text-xl font-bold">Rp312.000</p>
            </div>
            <a href="/pembayaran" class="block w-full text-center bg-gray-300 py-3 border border-gray-400 font-bold text-sm">Buat Pesanan</a>
        </div>
    </div>
</div>
@endsection