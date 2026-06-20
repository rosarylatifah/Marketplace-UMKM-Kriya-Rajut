@extends('layouts.pembeli')

@section('content')
<div class="py-10 flex justify-center items-start px-4">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-xl p-8 sm:p-12">
        <div class="text-center mb-10">
            <h1 class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-2">Pesananmu</h1>
            <p class="text-xl font-bold text-[#001f3f] uppercase tracking-[0.15em]">Cek Status Pesanan</p>
            <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        </div>

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-500 text-xs font-bold uppercase tracking-widest px-4 py-3 rounded-lg text-center">
            {{ session('error') }}
        </div>
        @endif

        <form action="/status-pesanan" method="GET" class="space-y-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-[0.2em] text-gray-500 mb-2">Email</label>
                <input type="email" name="email" placeholder="contoh@email.com"
                    class="w-full border border-gray-200 rounded-lg bg-[#F3F5F1] px-4 py-3.5 text-sm text-[#001f3f] placeholder-gray-300 focus:outline-none focus:border-[#001f3f] focus:bg-white transition-all duration-150">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-[0.2em] text-gray-500 mb-2">Kode Pesanan</label>
                <input type="text" name="kode" placeholder="contoh: #ORD-0012"
                    class="w-full border border-gray-200 rounded-lg bg-[#F3F5F1] px-4 py-3.5 text-sm text-[#001f3f] placeholder-gray-300 focus:outline-none focus:border-[#001f3f] focus:bg-white transition-all duration-150">
            </div>
            <button type="submit"
                class="w-full bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.25em] text-xs py-4 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                Cek Status Pesanan
            </button>
        </form>
    </div>
</div>
@endsection
