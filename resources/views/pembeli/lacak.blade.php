@extends('layouts.pembeli')

@section('content')
<div class="py-20 flex justify-center bg-pink-50 min-h-screen items-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-pink-100 w-full max-w-md">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-pink-600">Cek Status Pesanan</h1>
            <p class="text-gray-500">Masukkan detail pesanan Anda</p>
        </div>
        
        <form action="/status-pesanan" method="GET" class="space-y-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Email Pembeli</label>
                <input type="email" name="email" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-pink-500 focus:border-pink-500 transition-all outline-none" 
                    placeholder="nama@email.com" required>
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-pink-500 focus:border-pink-500 transition-all outline-none" 
                    placeholder="0812xxxx" required>
            </div>

            <button type="submit" 
                class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg transition-colors shadow-lg shadow-pink-200">
                Lacak Pesanan Sekarang
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400 italic">
                *Pastikan nomor telepon sesuai dengan saat pemesanan.
            </p>
        </div>
    </div>
</div>
@endsection