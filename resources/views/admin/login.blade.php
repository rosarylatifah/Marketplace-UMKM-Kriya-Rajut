<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kriya Rajut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-none border border-gray-400 w-full max-w-sm">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold uppercase text-black tracking-widest">Admin Login</h1>
        </div>

        <form action="/admin/dashboard" method="GET" class="space-y-5">
            <div>
                <label class="block mb-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Email Admin</label>
                <input type="email" class="w-full px-3 py-2 rounded-none bg-gray-50 border border-gray-400 focus:ring-0 focus:border-black outline-none text-sm" placeholder="admin@rajut.com" required>
            </div>
            <div>
                <label class="block mb-1 text-xs font-bold text-gray-600 uppercase tracking-wider">Password</label>
                <input type="password" class="w-full px-3 py-2 rounded-none bg-gray-50 border border-gray-400 focus:ring-0 focus:border-black outline-none text-sm" placeholder="••••••••" required>
            </div>
            
            <a href="/admin/dashboard" class="block w-full">
                <button type="button" class="w-full bg-pink-600 hover:bg-pink-700 text-white border border-gray-400 font-bold py-3 rounded-none uppercase text-sm tracking-widest transition-colors">
                    Masuk ke Panel
                </button>
            </a>

        </form>
    </div>

</body>
</html>