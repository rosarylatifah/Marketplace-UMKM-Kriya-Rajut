<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AdminAuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        return view('admin.login'); // Membuka file login.blade.php kamu
    }

    // 2. Proses Validasi & Login Admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencocokkan inputan dengan password ter-hash di DB
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Jika salah, balik ke halaman login dengan error text
        return back()->withErrors([
            'login_error' => 'Email atau Password yang kamu masukkan salah!',
        ])->onlyInput('email');
    }

    // 3. Tampilkan Form Minta Link Lupa Password
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    // 4. Kirim Link Reset Password ke Email Admin
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Fitur bawaan Laravel untuk mengurus token & kirim email otomatis
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password sudah dikirim ke email kamu!');
        }

        return back()->withErrors(['email' => 'Email admin tidak ditemukan.']);
    }

    // 5. Proses Logout Admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}