@extends('layouts.pembeli')

@section('content')
<div class="relative bg-white w-screen left-1/2 -translate-x-1/2 border-b border-gray-100 overflow-hidden mb-12 shadow-sm">
    <div class="flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 p-12 md:pl-[max(2rem,calc((100vw-80rem)/2+2rem))] lg:p-20 z-10">
            <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-4 block">Est. 2026</span>
            <h1 class="text-4xl lg:text-5xl font-bold text-[#001f3f] leading-[1.1] mb-6 uppercase tracking-tight">
                Eksplorasi <br> <span class="text-gray-400 font-light italic">Seni Rajut</span> <br> Modern.
            </h1>
            <p class="text-xs text-gray-500 leading-relaxed max-w-xs mb-8 uppercase tracking-widest">
                Koleksi buatan tangan dengan benang premium untuk gaya hidup minimalis.
            </p>
            <a href="/katalog" class="inline-block bg-[#001f3f] text-white px-10 py-4 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg">
                Lihat Koleksi
            </a>
        </div>
        
        <div class="md:w-1/2 h-[500px] relative">
            <img src="https://images.unsplash.com/photo-1608408881647-773665673100?q=80&w=1000&auto=format&fit=crop" 
                 class="w-full h-full object-cover grayscale brightness-90 hover:grayscale-0 transition-all duration-1000">
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/10 to-transparent"></div>
        </div>
    </div>
</div>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-[14px] font-bold text-[#001f3f] uppercase tracking-[0.3em]">Koleksi Pilihan</h2>
        <div class="h-[2px] w-12 bg-[#001f3f] mt-2"></div>
    </div>
    <a href="/katalog" class="text-[10px] font-bold text-gray-400 hover:text-[#001f3f] uppercase tracking-widest transition-colors">Lihat Semua Katalog &rarr;</a>
</div>

@php
$produk = [
    ['file' => 'amigurumibebek-rajut.jpg',          'nama' => 'Amigurumi Bebek',        'harga' => 85000,  'deskripsi' => 'Boneka rajut bebek kuning yang menggemaskan, empuk dan cocok untuk kado.'],
    ['file' => 'amigurumidoraemon-rajut.jpg',        'nama' => 'Amigurumi Doraemon',     'harga' => 95000,  'deskripsi' => 'Karakter favorit dalam bentuk rajutan tangan yang rapi dan detail.'],
    ['file' => 'amigurumimonster-rajut.jpg',         'nama' => 'Amigurumi Monster',      'harga' => 75000,  'deskripsi' => 'Monster kecil yang lucu dengan kombinasi warna benang premium.'],
    ['file' => 'amigurumimouse-rajut.jpg',           'nama' => 'Amigurumi Mouse',        'harga' => 80000,  'deskripsi' => 'Boneka tikus rajut dengan detail pakaian yang manis dan artistik.'],
    ['file' => 'amigurumiSmurf&Smurfette-rajut.jpg', 'nama' => 'Amigurumi Smurf',        'harga' => 120000, 'deskripsi' => 'Sepasang karakter Smurf ikonik untuk koleksi pajangan estetikmu.'],
    ['file' => 'cermin-rajut.jpg',                  'nama' => 'Cermin Rajut',           'harga' => 45000,  'deskripsi' => 'Cermin saku dengan bingkai rajutan bunga, praktis dibawa kemana saja.'],
    ['file' => 'dompet-rajut.jpg',                  'nama' => 'Dompet Rajut',           'harga' => 65000,  'deskripsi' => 'Dompet koin multifungsi dengan teknik rajut yang tebal dan kokoh.'],
    ['file' => 'gantungankunci-rajut.jpg',           'nama' => 'Gantungan Kunci Rajut',  'harga' => 25000,  'deskripsi' => 'Gantungan kunci handmade yang mempercantik tas atau kunci kendaraan.'],
    ['file' => 'hiasanpotbunga-rajut.jpg',           'nama' => 'Hiasan Pot Bunga',       'harga' => 35000,  'deskripsi' => 'Cover pot rajut untuk memberikan kesan hangat pada tanaman indoor.'],
    ['file' => 'jepitrambut-rajut.jpg',              'nama' => 'Jepit Rambut Rajut',     'harga' => 15000,  'deskripsi' => 'Aksesoris rambut minimalis yang dibuat dengan ketelitian tinggi.'],
    ['file' => 'setpakaiananakdino-rajut.jpg',       'nama' => 'Set Pakaian Anak Dino',  'harga' => 250000, 'deskripsi' => 'Setelan bayi motif dino yang lembut, nyaman untuk kulit si kecil.'],
    ['file' => 'setpakaiananakjerapah-rajut.jpg',    'nama' => 'Set Pakaian Jerapah',    'harga' => 245000, 'deskripsi' => 'Kostum rajut jerapah unik untuk sesi foto bayi yang menggemaskan.'],
    ['file' => 'setpakaiananakpink-rajut.jpg',       'nama' => 'Set Pakaian Anak Pink',  'harga' => 230000, 'deskripsi' => 'Setelan rajut pink elegan dengan bahan benang katun kualitas ekspor.'],
    ['file' => 'setpakaiananaksapi-rajut.jpg',       'nama' => 'Set Pakaian Anak Sapi',  'harga' => 245000, 'deskripsi' => 'Desain motif sapi yang lucu, cocok untuk perlengkapan harian bayi.'],
    ['file' => 'sweteranakpink-rajut.jpg',           'nama' => 'Sweater Anak Pink',      'harga' => 185000, 'deskripsi' => 'Sweater rajut hangat yang awet dan tidak mudah melar setelah dicuci.'],
];
@endphp

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-16">
    @foreach($produk as $i => $p)
    <div class="group bg-white border border-gray-200 p-3 flex flex-col items-center text-center transition-all hover:shadow-md">
        <div class="w-full bg-gray-50 aspect-square mb-4 border border-gray-100 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/produk/' . $p['file']) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $p['nama'] }}">
        </div>
        
        <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">{{ $p['nama'] }}</h3>
        <p class="text-[10px] text-gray-400 my-2 italic">Handmade premium quality.</p>
        
        <button data-modal-target="modal-home-{{ $i }}" data-modal-toggle="modal-home-{{ $i }}"
            class="mt-auto w-full bg-[#001f3f] text-white text-[9px] py-2.5 rounded-full hover:bg-gray-800 transition-all uppercase font-bold tracking-widest shadow-sm">
            Lihat Detail
        </button>

        {{-- Modal --}}
        <div id="modal-home-{{ $i }}" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/60 backdrop-blur-sm p-4">
            <div class="relative bg-white w-full max-w-3xl p-6 md:p-8">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    {{-- Gunakan nama file sebagai ID supaya produk yang sama tidak dobel di keranjang --}}
                    <input type="hidden" name="id"       value="{{ $p['file'] }}">
                    <input type="hidden" name="nama"     value="{{ $p['nama'] }}">
                    <input type="hidden" name="harga"    value="{{ $p['harga'] }}">
                    <input type="hidden" name="foto"     value="{{ asset('images/produk/' . $p['file']) }}">
                    <input type="hidden" name="deskripsi" value="{{ $p['deskripsi'] }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <img src="{{ asset('images/produk/' . $p['file']) }}" class="w-full aspect-square object-cover border border-gray-100">
                        <div class="flex flex-col">
                            <h2 class="text-xl font-bold uppercase">{{ $p['nama'] }}</h2>
                            <p class="text-lg font-bold text-gray-700 mb-4">Rp {{ number_format($p['harga'], 0, ',', '.') }}</p>
                            {{-- Deskripsi tampil di modal home --}}
                            <p class="text-[12px] text-gray-500 mb-6">{{ $p['deskripsi'] }}</p>
                            
                            <div class="mb-6">
                                <span class="text-[10px] font-bold block mb-2 uppercase">Jumlah</span>
                                <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded-full text-center text-xs font-bold">
                            </div>

                            <button type="submit" class="w-full bg-[#001f3f] text-white py-3.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
                <button data-modal-hide="modal-home-{{ $i }}" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection