<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriya Rajut - Marketplace UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-pink-50 flex flex-col min-h-screen">
    <nav class="bg-white border-b border-pink-100 sticky top-0 z-50">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3">
                <span class="self-center text-2xl font-bold whitespace-nowrap text-pink-600">Kriya Rajut</span>
            </a>
            <div class="hidden w-full md:block md:w-auto">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 md:mt-0 md:border-0 bg-white">
                    <li><a href="/" class="block py-2 px-3 text-pink-600 font-bold">Home</a></li>
                    <li><a href="/katalog" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Katalog</a></li>
                    <li><a href="/lacak-pesanan" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Lacak Pesanan</a></li>
                    <li><a href="/about" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Tentang Kami</a></li>
                    <a href="#" title="Keranjang">
                <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </a>
                </ul>
            </div>
        </div>
    </nav>

    <main class="max-w-screen-xl mx-auto p-4 flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12 pt-10 pb-6">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div>
                    <h3 class="font-bold text-lg mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#tentang-kami">Tentang Kami</a></li>
                        <li><a href="/pesanan">Pesanan Pelanggan</a></li>
                        <li><a href="/lacak-pesanan">Lacak Pesanan</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-gray-200 mb-6">
            
            <div class="flex flex-wrap justify-between text-xs text-gray-500 italic">
                <span>Batam, Kepulauan Riau</span>
                <span>+62 812-3456-7890</span>
                <span>rajut@gmail.com</span>
                <span>Made with love for gift lovers</span>
            </div>
            
            <p class="text-center text-xs text-gray-400 mt-8">
                © 2026 GiftHub. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>