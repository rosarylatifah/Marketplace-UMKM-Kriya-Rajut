<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Lupa Password</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-[#001f3f] text-center mb-2">Lupa Password Admin</h2>
        
        <p class="text-gray-500 text-sm text-center mb-6">Masukkan email admin. Setelah diklik, silakan buka link reset password-nya di file <span class="font-mono bg-gray-100 text-red-600 px-1 rounded">storage/logs/laravel.log</span></p>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg border border-green-100">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Admin</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-4 py-3 border rounded-xl text-gray-700 focus:outline-none focus:border-[#001f3f] @error('email') border-red-500 @enderror" 
                       placeholder="admin@gmail.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#001f3f] text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-800 transition-all shadow-md cursor-pointer">
                Proses Link Reset Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('admin.login') }}" class="text-xs font-bold text-[#001f3f] hover:underline">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>