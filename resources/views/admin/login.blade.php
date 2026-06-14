<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kriya Rajut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F5F1] flex items-center justify-center h-screen">

    <div class="bg-white border border-gray-200 rounded-xl w-full max-w-md p-12">

        {{-- Header --}}
        <div class="text-center mb-10">
            <p class="text-xl font-bold text-[#001f3f] tracking-[0.2em] uppercase mb-1">
                KRIYA<span class="text-gray-400 font-light">RAJUT</span>
            </p>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mt-3">Panel Admin</p>
            <div class="mt-4 h-px w-12 bg-[#001f3f] mx-auto"></div>
        </div>

        {{-- Form Login Admin --}}
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
            @csrf 

            {{-- Nampilin Alert kalau Email/Password Salah --}}
            @if($errors->has('login_error'))
                <div class="text-red-500 text-xs font-semibold text-center bg-red-50 p-3 rounded-lg border border-red-100">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first('login_error') }}
                </div>
            @endif

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Email Admin</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@rajut.com" required
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300 bg-transparent outline-none">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Password</label>
                    {{-- Link Lupa Password --}}
                    <a href="{{ route('admin.password.request') }}" class="text-[10px] text-gray-400 hover:text-[#001f3f] transition-colors font-medium">Lupa Password?</a>
                </div>
                <input type="password" name="password" placeholder="••••••••" required
                    class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-[13px] py-3 px-0 transition-all duration-300 font-medium placeholder-gray-300 bg-transparent outline-none">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#001f3f] hover:bg-[#003366] text-white font-bold py-4 rounded-full uppercase text-[11px] tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                    Masuk ke Panel
                </button>
            </div>

        </form>

    </div>

</body>
</html>