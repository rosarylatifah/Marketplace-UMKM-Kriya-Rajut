@extends('layouts.pembeli')

@section('content')
<div class="py-20 flex justify-center">
    <div class="w-full max-w-md border border-gray-400 p-10 bg-white text-center shadow-sm">
        <h2 class="text-lg font-bold uppercase mb-8 tracking-widest">Cek Status Pesanan</h2>
        
        <form action="/status-pesanan" method="GET" class="space-y-6 text-left">
            <div>
                <label class="block text-xs font-bold mb-2 uppercase text-gray-500">Email</label>
                <input type="email" name="email" class="w-full border-gray-400 bg-gray-100 p-3 text-sm focus:ring-0 focus:border-gray-800 outline-none">
            </div>
            
            <div>
                <label class="block text-xs font-bold mb-2 uppercase text-gray-500">Nomor Telpon</label>
                <input type="text" name="phone" class="w-full border-gray-400 bg-gray-100 p-3 text-sm focus:ring-0 focus:border-gray-800 outline-none">
            </div>

            <button type="submit" class="w-full bg-gray-300 border border-gray-400 py-3 text-xs font-bold uppercase hover:bg-gray-400 transition-all">
                Cek Status Pesanan
            </button>
        </form>
    </div>
</div>
@endsection