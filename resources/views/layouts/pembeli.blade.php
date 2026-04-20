<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriya Rajut - Marketplace UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-pink-50 flex flex-col min-h-screen">
    <nav class="bg-white border-b border-pink-100 sticky top-0 z-50">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3">
                <span class="self-center text-2xl font-bold whitespace-nowrap text-pink-600">Kriya Rajut</span>
            </a>
            <div class="hidden w-full md:block md:w-auto">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 md:items-center md:mt-0 md:border-0 bg-white">
                    <li><a href="/" class="block py-2 px-3 text-pink-600 font-bold">Home</a></li>
                    <li><a href="/katalog" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Katalog</a></li>
                    <li><a href="/lacak-pesanan" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Lacak
                            Pesanan</a></li>
                    <li><a href="#tentang-kami" class="block py-2 px-3 text-gray-700 hover:text-pink-600">Tentang Kami</a></li>
                    <a href="/keranjang" title="Keranjang">
                        <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                    </a>
                </ul>
            </div>
        </div>
    </nav>

    <main class="max-w-screen-xl mx-auto p-4 flex-grow">
        @yield('content')
    </main>

    <footer id="tentang-kami" class="bg-white border-t border-gray-200 mt-20 pt-16 pb-3">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <div>
                    <h3 class="font-bold text-gray-800 uppercase tracking-wider mb-6">Tentang Kami</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sejak 2020, kami mengembangkan usaha kriya rajut buatan tangan dengan berbagai produk unik dan
                        menarik. Kami berkomitmen untuk menghadirkan produk berkualitas dengan sentuhan handmade.
                        Website ini dibuat agar pelanggan dapat berbelanja dengan lebih mudah dan nyaman.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 uppercase tracking-wider mb-6">Dukungan Pelanggan</h3>
                    <ul class="space-y-4 text-sm text-gray-600">
                        <li><a href="/lacak-pesanan" class="hover:text-pink-600 transition-colors">Lacak Pesanan</a>
                        </li>
                        <li><a href="#" class="hover:text-pink-600 transition-colors">Panduan Pemesanan</a></li>
                        <li><a href="#" class="hover:text-pink-600 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 uppercase tracking-wider mb-6">Terhubung Dengan Kami</h3>
                    <ul class="space-y-4 text-sm text-gray-600">
                        <li class="flex items-center gap-3">
                            <a href="https://instagram.com/akun_kamu" target="_blank"
                                class="flex items-center gap-3 hover:text-pink-600 transition-colors">
                                <i class="fab fa-instagram text-xl text-gray-700"></i>
                                <span>@blabla</span>
                            </a>
                        </li>
                        <li class="flex items-center gap-3">
                            <a href="https://wa.me/6285778092881" target="_blank"
                                class="flex items-center gap-3 hover:text-green-600 transition-colors">
                                <i class="fab fa-whatsapp text-xl text-gray-700"></i>
                                <span>+62 857 7809 2881</span>
                            </a>
                        </li>
                        <li class="flex items-center gap-3">
                            <a href="mailto:blabla@gmail.com"
                                class="flex items-center gap-3 hover:text-blue-600 transition-colors">
                                <i class="far fa-envelope text-xl text-gray-700"></i>
                                <span>blabla@gmail.com</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-3 text-center">
                <p class="text-xs text-gray-400 padding-y-4">
                    © 2026 Kriya Rajut. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>