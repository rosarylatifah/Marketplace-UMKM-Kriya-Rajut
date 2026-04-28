@extends('layouts.pembeli')

@section('content')

@php
    $total = 0;
    if(session('cart')) {
        foreach(session('cart') as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
    }
@endphp

<div class="py-12 flex justify-center items-center">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-12">

        <div class="text-center mb-10">
            <h1 class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mb-2">Transaksi</h1>
            <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Pembayaran</p>
            <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        </div>

        <div class="space-y-6">

            <div class="bg-[#F3F5F1] rounded-lg px-6 py-5 flex justify-between items-center">
                <span class="text-[11px] uppercase tracking-[0.2em] text-gray-400">Total Pembayaran</span>
                <span class="text-lg font-bold text-[#001f3f]">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="border border-gray-100 rounded-lg px-6 py-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">Transfer ke Rekening</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-[#001f3f] text-sm tracking-wide">Latifah Intan Rosary</p>
                        <p class="text-sm text-gray-400 mt-0.5">Bank BNI &nbsp;·&nbsp; 1948 2498 137</p>
                    </div>
                    <div class="bg-[#F3F5F1] rounded-lg px-3 py-1.5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">BNI</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-2">Bukti Pembayaran</label>
                <label class="flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-200 rounded-lg py-8 cursor-pointer hover:border-[#001f3f] hover:bg-[#F3F5F1] transition-all duration-150">
                    <i class="fa-regular fa-image text-2xl text-gray-300 mb-2"></i>
                    <span class="text-[11px] uppercase tracking-widest text-gray-400">Klik untuk upload bukti transfer</span>
                    <span class="text-[10px] text-gray-300 mt-1">PNG, JPG hingga 5MB</span>
                    <input type="file" class="hidden">
                </label>
            </div>

            <a href="/berhasil"
                class="block w-full text-center bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.25em] text-[11px] py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                Selesai
            </a>

        </div>
    </div>
</div>

@endsection