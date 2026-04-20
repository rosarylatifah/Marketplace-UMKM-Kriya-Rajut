@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Ringkasan</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 bg-white border rounded-xl shadow-sm">
        <h5 class="text-gray-500 text-sm font-semibold uppercase">Stok Produk</h5>
        <p class="text-3xl font-bold text-pink-600">12</p>
    </div>
    <div class="p-6 bg-white border rounded-xl shadow-sm">
        <h5 class="text-gray-500 text-sm font-semibold uppercase">Pesanan Baru</h5>
        <p class="text-3xl font-bold text-blue-600">5</p>
    </div>
    <div class="p-6 bg-white border rounded-xl shadow-sm">
        <h5 class="text-gray-500 text-sm font-semibold uppercase">Total Kategori</h5>
        <p class="text-3xl font-bold text-green-600">3</p>
    </div>
</div>
@endsection