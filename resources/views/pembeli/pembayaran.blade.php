@extends('layouts.pembeli')

@section('content')
<div class="py-20 flex flex-col items-center">
    <div class="w-full max-w-2xl border border-gray-400 p-12 bg-white text-center">
        <h1 class="text-2xl font-bold mb-10">Pembayaran</h1>
        <div class="space-y-6 text-left mb-10">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Pembayaran:</span>
                <span class="font-bold">Rp312.0000</span>
            </div>
            <div class="text-gray-800 text-sm">
                <p class="font-bold">Latifah Intan Rosary</p>
                <p>Bank BNI 19482498137</p>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Bukti Pembayaran</label>
                <input type="file" class="w-full border border-gray-400 bg-gray-200 p-2 text-xs">
            </div>
        </div>
        <a href="/berhasil" class="inline-block bg-gray-300 px-12 py-2 border border-gray-400 font-bold text-sm uppercase">Selesai</a>
    </div>
</div>
@endsection