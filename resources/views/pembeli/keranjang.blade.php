@extends('layouts.pembeli')

@section('content')
<div class="py-10">
    <button onclick="window.history.back()" class="text-gray-800 mb-6 font-medium">&lt; Kembali</button>
    <h1 class="text-2xl font-bold mb-8">Keranjang Belanja</h1>

    <div class="hidden border border-gray-400 p-20 text-center bg-white">
        <svg class="w-20 h-20 mx-auto mb-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <h2 class="text-xl font-bold mb-2">Keranjang Anda Kosong</h2>
        <p class="text-gray-500 mb-6">Belum ada produk yang ditambahkan</p>
        <a href="/katalog" class="bg-gray-300 px-10 py-2 text-sm font-bold border border-gray-400">Mulai Belanja</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <div class="border border-gray-400 p-4 flex gap-4 bg-white relative">
                <div class="w-32 h-32 bg-gray-200 border border-gray-300 flex-shrink-0 flex items-center justify-center text-xs text-gray-400 uppercase">Foto Produk</div>
                <div class="flex-grow">
                    <h3 class="font-bold text-lg">Tas Rajut Boboho</h3>
                    <p class="text-gray-800">Rp. 150.000</p>
                    <div class="absolute bottom-4 right-4 flex flex-col items-end">
                        <div class="flex border border-gray-400">
                            <button class="px-2 bg-gray-200 border-r border-gray-400">&lt;</button>
                            <span class="px-4 py-1 bg-white">1</span>
                            <button class="px-2 bg-gray-200 border-l border-gray-400">&gt;</button>
                        </div>
                        <p class="text-[10px] mt-1 italic text-gray-500">Subtotal: Rp150.000</p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-400 p-4 flex gap-4 bg-white relative">
                <div class="w-32 h-32 bg-gray-200 border border-gray-300 flex-shrink-0 flex items-center justify-center text-xs text-gray-400 uppercase">Foto Produk</div>
                <div class="flex-grow">
                    <h3 class="font-bold text-lg">Dompet Mini Rajut</h3>
                    <p class="text-gray-800">Rp. 65.000</p>
                    <div class="absolute bottom-4 right-4 flex flex-col items-end">
                        <div class="flex border border-gray-400">
                            <button class="px-2 bg-gray-200 border-r border-gray-400">&lt;</button>
                            <span class="px-4 py-1 bg-white">2</span>
                            <button class="px-2 bg-gray-200 border-l border-gray-400">&gt;</button>
                        </div>
                        <p class="text-[10px] mt-1 italic text-gray-500">Subtotal: Rp130.000</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border border-gray-400 p-6 bg-white h-fit">
            <h3 class="font-bold mb-4">Total (Tidak Termasuk Ongkir)</h3>
            <div class="flex justify-between text-sm mb-2"><span>Subtotal</span><span>Rp 280.000</span></div>
            <div class="flex justify-between text-sm mb-4"><span>Total Produk</span><span>2 produk</span></div>
            <p class="text-[10px] text-gray-500 italic mb-6">*Ongkos kirim akan dihitung pada halaman checkout</p>
            <a href="/checkout" class="block w-full text-center bg-gray-300 py-2 border border-gray-400 font-bold text-sm">Checkout</a>
        </div>
    </div>
</div>
@endsection