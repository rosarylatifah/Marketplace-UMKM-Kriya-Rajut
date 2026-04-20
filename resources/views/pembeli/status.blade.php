@extends('layouts.pembeli')

@section('content')
<div class="py-10">
    <h1 class="text-2xl font-bold mb-8 uppercase tracking-tight">Status Pesanan</h1>

    <div class="space-y-4 mb-10">
        <div class="border border-gray-400 p-6 bg-white flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-400 font-bold uppercase">Foto Produk</div>
                <div>
                    <h3 class="font-bold text-lg">Tas Rajut Boboho</h3>
                    <p class="text-sm text-green-600 italic">Dalam Perjalanan</p>
                </div>
            </div>
            <a href="https://wa.me/6281234567890" class="flex items-center gap-2 border border-gray-400 bg-gray-200 px-6 py-2 text-sm font-bold hover:bg-gray-300">
                <span>💬</span> Kontak Pembeli
            </a>
        </div>

        <div class="border border-gray-400 p-6 bg-white flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-400 font-bold uppercase">Foto Produk</div>
                <div>
                    <h3 class="font-bold text-lg">Dompet Mini Rajut</h3>
                    <p class="text-sm text-green-600 italic">Dalam Perjalanan</p>
                </div>
            </div>
            <a href="https://wa.me/6281234567890" class="flex items-center gap-2 border border-gray-400 bg-gray-200 px-6 py-2 text-sm font-bold hover:bg-gray-300">
                <span>💬</span> Kontak Pembeli
            </a>
        </div>
    </div>

    <div class="text-center">
        <a href="/" class="bg-gray-300 border border-gray-400 px-10 py-3 text-xs font-bold uppercase hover:bg-gray-400">Mulai Belanja</a>
    </div>
</div>
@endsection