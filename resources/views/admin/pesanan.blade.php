@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Pesanan Masuk</h1>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-white uppercase bg-pink-600">
            <tr>
                <th class="px-6 py-3">ID Pesanan</th>
                <th class="px-6 py-3">Nama Pembeli</th>
                <th class="px-6 py-3">Total Harga</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b">
                <th class="px-6 py-4 font-bold text-gray-900">#ORD-001</th>
                <td class="px-6 py-4">Andi Pembeli</td>
                <td class="px-6 py-4">Rp 150.000</td>
                <td class="px-6 py-4">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                </td>
                <td class="px-6 py-4">
                    <button class="text-white bg-blue-700 hover:bg-blue-800 px-3 py-1 rounded-lg text-xs">Detail</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection