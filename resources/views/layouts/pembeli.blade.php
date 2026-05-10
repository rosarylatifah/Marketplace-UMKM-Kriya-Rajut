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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-nude-soft { background-color: #fdf5f5; } 
    </style>
</head>

<body class="bg-[#F3F5F1] flex flex-col min-h-screen">
    {{-- Navbar --}}
    <nav class="bg-white/90 backdrop-blur-sm sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-5">
            <a href="/" class="flex items-center space-x-3">
                <span class="self-center text-xl font-bold whitespace-nowrap text-[#001f3f] tracking-[0.2em] uppercase">
                    Kriya<span class="text-gray-400 font-light">Rajut</span>
                </span>
            </a>
            
            <div class="hidden w-full md:block md:w-auto">
                <ul class="flex flex-col md:flex-row md:space-x-10 md:items-center bg-transparent">
                    <li>
                        <a href="/" class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('/') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Beranda</a>
                    </li>
                    <li>
                        {{-- PERBAIKAN: Menggunakan named route 'katalog' agar parameter kategori bisa terbaca --}}
                        <a href="{{ route('katalog') }}" class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('katalog*') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Katalog</a>
                    </li>
                    <li>
                        <a href="/lacak-pesanan" class="block py-2 text-[11px] uppercase tracking-[0.2em] transition-all {{ request()->is('lacak-pesanan*') ? 'text-[#001f3f] font-bold border-b-2 border-[#001f3f]' : 'text-gray-500 hover:text-[#001f3f]' }}">Lacak</a>
                    </li>
                    <li class="relative p-2">
                        <a href="{{ route('keranjang') }}" class="group flex items-center">
                            <i class="fa-solid fa-bag-shopping text-lg {{ request()->is('keranjang*') ? 'text-pink-600' : 'text-gray-700 group-hover:text-[#001f3f]' }} transition-colors"></i>
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

    <main class="flex-grow max-w-7xl mx-auto w-full px-4 lg:px-0">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer id="tentang-kami" class="bg-[#EAE4DD] border-t border-gray-100 mt-20 pt-16 pb-8 text-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                 <div class="md:col-span-2">
                    <h3 class="text-lg font-bold text-[#001f3f] uppercase tracking-[0.3em] mb-6">Kriya Rajut</h3>
                    <p class="text-[13px] text-gray-700 leading-relaxed max-w-sm">
                        Menghadirkan kehangatan dalam setiap simpul. Kami berfokus pada kualitas benang premium dan teknik rajutan tangan otentik untuk menciptakan produk yang estetik dan tahan lama.
                    </p>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-6">Dukungan Pengguna</h3>
                    <ul class="space-y-3 text-[11px] text-gray-600 uppercase tracking-wider">
                        <li><a href="/panduan" class="hover:text-[#001f3f] transition-colors">Panduan Pengguna</a></li>
                        <li><a href="/FAQ" class="hover:text-[#001f3f] transition-colors">Bantuan FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest mb-6">Kontak</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="fab fa-whatsapp text-xs"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center text-gray-700 hover:bg-[#001f3f] hover:text-white transition-all overflow-hidden">
                            <i class="far fa-envelope text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

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