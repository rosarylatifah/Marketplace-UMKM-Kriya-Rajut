<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kriya Rajut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-pink-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-pink-600">Admin Login</h1>
            <p class="text-gray-500">Marketplace UMKM Kriya Rajut</p>
        </div>
        <form action="/admin/dashboard" method="GET" class="space-y-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Email Admin</label>
                <input type="email" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-pink-500 focus:border-pink-500" placeholder="admin@rajut.com" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input type="password" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-pink-500 focus:border-pink-500" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg transition-colors">
                Masuk ke Panel
            </button>
        </form>
    </div>
</body>
</html>