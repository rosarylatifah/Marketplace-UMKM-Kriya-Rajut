@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Kategori Produk</h1>
    <button class="text-white bg-pink-600 hover:bg-pink-600 font-medium rounded-lg text-sm px-5 py-2.5">
        + Tambah Kategori
    </button>
</div>

<div class="max-w-2xl relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-6 py-3">No</th>
                <th class="px-6 py-3">Nama Kategori</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">1</td>
                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Tas Rajut</th>
                <td class="px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">2</td>
                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Aksesoris</th>
                <td class="px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection