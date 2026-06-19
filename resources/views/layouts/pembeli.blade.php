<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriya Rajut - Marketplace UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-nude-soft {
            background-color: #fdf5f5;
        }
    </style>
</head>

{{-- MENAMBAHKAN overflow-x-hidden BIAR SLIDER NGGAK MERUSAK LEBAR LAYAR DESKTOP --}}
<body class="bg-[#F3F5F1] flex flex-col min-h-screen overflow-x-hidden">
    {{-- Navbar --}}
    <nav class="bg-white/90 backdrop-blur-sm sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-5">
            <a href="/" class="flex items-center space-x-3">
                <span class="self-center text-xl font-bold whitespace-nowrap text-[#001f3f] tracking-[0.2em] uppercase">
                    Stitchy<span class="text-gray-400 font-light">Sist</span>
                </span>
            </a>

            <div class="hidden w-full md:block md:w-auto">
                <ul class="flex flex-col md:flex-row md:space-x-10 md:items-center bg-transparent">
                    <li>
                        <a href="/"
                            class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('/') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('katalog') }}"
                            class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('katalog*') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Katalog</a>
                    </li>
                    <li>
                        <a href="{{ url()->current() }}#tentang-kami"
                            class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all text-gray-500 hover:text-[#001f3f]">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="/lacak-pesanan"
                            class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('lacak-pesanan*') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Lacak</a>
                    </li>
                    <li class="relative p-2">
                        <a href="/keranjang" class="group flex items-center">
                            <i class="fa-solid fa-bag-shopping text-lg {{ request()->is('keranjang*') ? 'text-pink-600' : 'text-gray-700 group-hover:text-[#001f3f]' }}"></i>
                            <span class="absolute top-1 right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#001f3f] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#001f3f]"></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA: DIBERI RELATIVE AGAR POSISI ABSOLUTE SLIDER DI BERANDA MENGACU PADA BOX INI --}}
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 lg:px-0 relative">
        @yield('content')
    </main>

    {{-- TOMBOL WHATSAPP MELAYANG --}}
<a href="https://wa.me/6285778092881" target="_blank"
   class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200">
    <span class="absolute inline-flex h-full w-full rounded-full bg-[#25D366] opacity-40 animate-ping"></span>
    <i class="fab fa-whatsapp text-2xl relative"></i>
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

    {{-- Footer --}}
    <footer id="tentang-kami" class="bg-[#EAE4DD] border-t border-gray-100 mt-20 pt-16 pb-8 text-gray-800 w-full">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">

                {{-- Kolom 1: Deskripsi StitchySist --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold text-[#001f3f] uppercase tracking-[0.3em] mb-6">StitchySist</h3>
                    <p class="text-[13px] text-gray-700 leading-relaxed max-w-sm">
                        Kami adalah usaha mikro kriya rajut rumahan buatan tangan yang digerakkan oleh 3 orang pengrajin
                        lokal Batam. Menggunakan benang premium dan teknik rajutan tangan otentik, kami menghadirkan
                        produk estetik yang dibuat khusus penuh kehangatan untuk Anda. </p>
                </div>

                {{-- Kolom 2: Dukungan Pengguna --}}
                <div>
                    <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-6">Dukungan Pengguna</h3>
                    <ul class="space-y-3 text-[11px] text-gray-600 uppercase tracking-wider">
                        <li><a href="/panduan" class="hover:text-[#001f3f] transition-colors">Panduan Pengguna</a></li>
                        <li><a href="/FAQ" class="hover:text-[#001f3f] transition-colors">Bantuan FAQ</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Kontak, Alamat & Google Maps --}}
                <div>
                    <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-6">Kontak & Rumah Produksi</h3>

                    {{-- Alamat Klik-able ke Google Maps --}}
                    <a href="https://maps.google.com/?q=Perumahan+Mediterania+Batam+Kota" target="_blank"
                        class="text-[12px] text-gray-700 leading-relaxed mb-6 flex items-start gap-2.5 group hover:text-[#001f3f] transition-colors">
                        <div class="w-7 h-7 rounded-sm bg-[#001f3f]/5 flex items-center justify-center text-[#001f3f] flex-shrink-0 group-hover:bg-[#001f3f] group-hover:text-white transition-all duration-300 mt-0.5">
                            <i class="fa-solid fa-map-location-dot text-xs"></i>
                        </div>
                        <span> 
                            <span class="underline decoration-gray-300 decoration-dashed underline-offset-4 group-hover:decoration-[#001f3f]">
                                Perumahan Sentosa Perdana Blok C No.12, Kec. Sagulung, Kel. Tembesi, Kota Batam, Kepulauan Riau 
                            </span>
                        </span>
                    </a>

                    {{-- Media Sosial --}}
                    <div class="flex gap-4 pt-2">
                        <a href="https://instagram.com/stitchysist" target="_blank"
                            class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="https://wa.me/6285778092881" target="_blank"
                            class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="fab fa-whatsapp text-xs"></i>
                        </a>
                        <a href="mailto:stitchysist@gmail.com"
                            class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="far fa-envelope text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Hak Cipta & Kebijakan --}}
            <div class="border-t border-gray-300 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest">
                    © 2026 Kriya Rajut Studio. All Rights Reserved.
                </p>
                <div class="flex gap-6 text-[10px] text-gray-500 uppercase tracking-widest">
                    <a href="#" class="hover:text-gray-700 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-700 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>