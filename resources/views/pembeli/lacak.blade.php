@extends('layouts.pembeli')

@section('content')
<div class="py-12 flex justify-center items-center">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-12">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-[11px] uppercase tracking-[0.3em] text-gray-400 mb-2">Pesananmu</h1>
            <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Cek Status Pesanan</p>
            <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        </div>

        {{-- Form --}}
        <form action="/status-pesanan" method="GET" class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Email</label>
                <input type="email" name="email" placeholder="contoh@email.com"
                    class="w-full border border-gray-200 rounded-lg bg-[#F3F5F1] px-4 py-3.5 text-sm text-[#001f3f] placeholder-gray-300 focus:outline-none focus:border-[#001f3f] focus:bg-white transition-all duration-150">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Nomor Telepon</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                    class="w-full border border-gray-200 rounded-lg bg-[#F3F5F1] px-4 py-3.5 text-sm text-[#001f3f] placeholder-gray-300 focus:outline-none focus:border-[#001f3f] focus:bg-white transition-all duration-150">
            </div>

            <button type="submit"
                class="w-full bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.25em] text-[11px] py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                Cek Status Pesanan
            </button>
        </form>

    </div>
</div>
@endsection