@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-black uppercase tracking-tight">Dashboard Admin</h1>
    <p class="text-gray-500 text-sm">Selamat datang, Admin</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="p-5 bg-white border border-black rounded-none">
        <h5 class="text-black text-sm font-semibold mb-3">Produk Tersedia</h5>
        <p class="text-4xl font-bold mb-1">18</p>
        <p class="text-xs text-gray-500 font-medium">dari 8 jenis produk</p>
    </div>

    <div class="p-5 bg-white border border-black rounded-none">
        <h5 class="text-black text-sm font-semibold mb-3">Pesanan Terbaru</h5>
        <p class="text-4xl font-bold mb-1">1</p>
        <p class="text-xs text-gray-500 font-medium">Menunggu Konfirmasi</p>
    </div>

    <div class="p-5 bg-white border border-black rounded-none">
        <h5 class="text-black text-sm font-semibold mb-3">Pesanan Aktif</h5>
        <p class="text-4xl font-bold mb-1">1</p>
        <p class="text-xs text-gray-500 font-medium">Belum Dikirim</p>
    </div>

    <div class="p-5 bg-white border border-black rounded-none">
        <h5 class="text-black text-sm font-semibold mb-3">Total Pendapatan</h5>
        <p class="text-2xl font-bold mb-1">Rp600.000</p>
        <p class="text-xs text-gray-500 font-medium">Bulan ini</p>
    </div>
</div>

<div class="p-8 bg-white border border-black rounded-none">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-lg font-bold text-black uppercase">Aktivitas Terbaru</h2>
        <a href="{{ url('/admin/lihat-semua') }}" class="text-sm font-medium text-black hover:underline">Lihat Semua</a>
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-center p-5 bg-gray-100 border border-black">
            <div>
                <p class="font-bold text-black tracking-tight">ORD-1775410603875</p>
                <p class="text-xs text-gray-600 mt-1">Budi Sanjaya</p>
                <p class="text-xs text-gray-600">Rp312.000</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-xs uppercase tracking-widest text-black mb-1">Diproses</p>
                <p class="text-[10px] text-gray-500 font-medium">12/02/2026</p>
            </div>
        </div>

        <div class="flex justify-between items-center p-5 bg-gray-100 border border-black">
            <div>
                <p class="font-bold text-black tracking-tight">ORD-1775410603875</p>
                <p class="text-xs text-gray-600 mt-1">Bayu Suwono</p>
                <p class="text-xs text-gray-600">Rp740.000</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-xs uppercase tracking-widest text-black mb-1">Dikirim</p>
                <p class="text-[10px] text-gray-500 font-medium">07/02/2026</p>
            </div>
        </div>

        <div class="flex justify-between items-center p-5 bg-gray-100 border border-black">
            <div>
                <p class="font-bold text-black tracking-tight">ORD-1775410603875</p>
                <p class="text-xs text-gray-600 mt-1">Lila Lolali</p>
                <p class="text-xs text-gray-600">Rp125.000</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-xs uppercase tracking-widest text-black mb-1">Selesai</p>
                <p class="text-[10px] text-gray-500 font-medium">22/01/2026</p>
            </div>
        </div>
    </div>
</div>
@endsection