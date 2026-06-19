<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kriya Rajut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F5F1]">

    {{-- Navbar Top --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-100">
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-[#001f3f] font-bold tracking-[0.2em] uppercase text-xl">
                Stitchy<span class="text-gray-400 font-light">Sist</span>
                <span class="text-[13px] text-gray-300 font-normal tracking-widest ml-2">/ ADMIN</span>
            </span>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="fixed top-0 left-0 z-40 w-56 h-screen pt-16 bg-white border-r border-gray-100">
        <div class="h-full px-4 py-8 overflow-y-auto flex flex-col justify-between">

            <ul class="space-y-1">
                <li>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-gray-300 font-bold px-3 mb-3">Menu</p>
                </li>
                <li>
                    <a href="/admin/dashboard"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/dashboard') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-chart-simple w-4 text-center"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/admin/produk-list"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/produk-list*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-box w-4 text-center"></i>
                        Kelola Produk
                    </a>
                </li>


                {{-- 🆕 MENU BARU: Konfirmasi Pesanan (Ditaruh di awal alur transaksi) --}}
                <li>
                    <a href="/admin/pesanan-konfirmasi"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/pesanan-konfirmasi*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-clipboard-check w-4 text-center"></i>
                        Konfirmasi Pesanan
                    </a>
                </li>

                <li>
                    <a href="/admin/pesanan-masuk"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/pesanan-masuk*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-inbox w-4 text-center"></i>
                        Pesanan Masuk
                    </a>
                </li>

<li>
    <a href="/admin/pesanan-pengajuan-batal"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
        {{ request()->is('admin/pesanan-pengajuan-batal*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
        <i class="fa-solid fa-rotate-left w-4 text-center"></i>
        Pengajuan Batal
    </a>
</li>

                {{-- MENU: Pesanan Batal --}}
                <li>
                    <a href="/admin/pesanan-batal"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/pesanan-batal*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-ban w-4 text-center"></i>
                        Pesanan Batal
                    </a>
                </li>
                <li>
                    <a href="/admin/pesanan-selesai"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all duration-150
                        {{ request()->is('admin/pesanan-selesai*') ? 'bg-[#001f3f] text-white' : 'text-gray-400 hover:text-[#001f3f] hover:bg-[#F3F5F1]' }}">
                        <i class="fa-solid fa-circle-check w-4 text-center"></i>
                        Pesanan Selesai
                    </a>
                </li>
            </ul>

            {{-- Logout --}}
            <div class="border-t border-gray-100 pt-4">
                <a href="/admin/login"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                    Log Out
                </a>
            </div>

        </div>
    </aside>

    {{-- Main Content --}}
    <div class="sm:ml-56 pt-16">
        <div class="p-8 min-h-screen">
            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>