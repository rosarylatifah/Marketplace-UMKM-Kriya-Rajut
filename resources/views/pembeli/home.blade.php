@extends('layouts.pembeli')

@section('content')
<div class="bg-gray-50 border border-gray-200 rounded-lg p-8 flex items-center justify-between mb-10">
    <div class="max-w-md">
        <h1 class="text-3xl font-bold text-gray-800 leading-tight">
            Produk rajutan tangan lokal Indonesia yang dibuat dengan sepenuh hati.
        </h1>
    </div>
    <div class="hidden md:block w-1/3 bg-white border border-dashed border-gray-300 rounded-lg h-40 flex items-center justify-center">
        <span class="text-gray-400 italic">foto produk</span>
    </div>
</div>

<div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-10 border-b border-gray-100 pb-6">
    @foreach(['Semua', 'Pakaian', 'Aksesoris', 'Dekorasi', 'Amigurumi', 'Tas & Wadah'] as $kategori)
        <a href="#" class="text-sm font-medium text-gray-600 hover:text-pink-600 transition-colors uppercase tracking-wider">
            {{ $kategori }}
        </a>
    @endforeach
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
    @for ($i = 1; $i <= 8; $i++)
    <div class="bg-white border border-gray-200 rounded-sm p-4 flex flex-col items-center text-center">
        <div class="w-full bg-gray-100 h-48 mb-4 border border-gray-200 flex items-center justify-center text-gray-400">foto produk</div>
        <h3 class="font-bold text-gray-800 lowercase text-sm">tas rajut {{ $i }}</h3>
        <p class="text-[10px] text-gray-500 my-2">Tas rajut handmade dengan material benang katun premium.</p>
        
        <button data-modal-target="modal-produk-{{ $i }}" data-modal-toggle="modal-produk-{{ $i }}" class="mt-auto border border-gray-800 text-gray-800 text-[10px] py-2 px-6 hover:bg-gray-800 hover:text-white transition-all uppercase font-bold">
            Lihat Detail
        </button>

        <div id="modal-produk-{{ $i }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-4xl h-full md:h-auto">
        <div class="relative bg-white border border-gray-400 p-6 md:p-10">
            <button data-modal-hide="modal-produk-{{ $i }}" class="flex items-center text-gray-800 mb-6 font-medium">
                <span class="mr-2">&lt;</span> Kembali
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border border-gray-300 p-8">
                <div class="aspect-square bg-white border border-gray-300 flex items-center justify-center">
                    <span class="text-2xl text-gray-400 font-medium">Foto Produk</span>
                </div>

                <div class="text-left space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800">Nama Produk</h2>
                    <p class="text-xl font-semibold text-gray-800 font-sans">Rp. 150.000</p>
                    
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Tas rajut handmade dengan material benang katun premium yang lembut dan kuat. Desainnya yang timeless dan tekstur rajutan yang unik bikin outfit harian lo makin estetik.
                    </p>
                    
                    <p class="text-sm text-gray-600">Tersedia (10 stok)</p>
                    
                    <div>
                        <label class="block text-sm mb-1">Jumlah</label>
                        <input type="number" value="1" min="1" class="w-24 bg-gray-200 border-none p-2 text-center text-sm focus:ring-0">
                    </div>

                    <button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 text-sm font-medium">
                        +tambah ke keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    @endfor
</div>

@endsection