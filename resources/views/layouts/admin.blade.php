<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kriya Rajut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5">
            <span class="self-center text-xl font-bold text-pink-600">Admin Kriya Rajut</span>
        </div>
    </nav>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0">
        <div class="h-full px-3 pb-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <li><a href="/admin/dashboard" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-pink-50">Dashboard</a></li>
                <li><a href="{{ route('admin.produk.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-pink-50">Kelola Produk</a></li>
                <li><a href="/admin/pesanan-masuk" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-pink-50">Pesanan Masuk</a></li>
                <li><a href="/admin/pesanan-selesai" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-pink-50">Pesanan Selesai</a></li>
                <li class="pt-4 mt-4 border-t border-gray-200">
                    <a href="/admin/login" class="flex items-center p-2 text-red-600 hover:bg-red-50 rounded-lg font-bold">
                    Log Out
                </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            @yield('content')
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>