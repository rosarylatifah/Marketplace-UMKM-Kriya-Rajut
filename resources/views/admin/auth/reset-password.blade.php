<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reset Password Baru</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-[#001f3f] text-center mb-2">Password Baru</h2>
        <p class="text-gray-500 text-sm text-center mb-6">Silakan buat password baru yang aman untuk akun admin lu.</p>

        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-3 border rounded-xl text-gray-700 focus:outline-none focus:border-[#001f3f] @error('password') border-red-500 @enderror" 
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required 
                       class="w-full px-4 py-3 border rounded-xl text-gray-700 focus:outline-none focus:border-[#001f3f]" 
                       placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="w-full bg-[#001f3f] text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-800 transition-all shadow-md cursor-pointer">
                Perbarui Password
            </button>
        </form>
    </div>
</body>
</html>