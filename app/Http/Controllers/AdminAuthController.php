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

<<<<<<< HEAD
        // PENTING: Tambahin Password::broker('admins') biar gak tabrakan sama user biasa
        $status = Password::broker('admins')->sendResetLink($request->only('email'));
=======
        // Fitur bawaan Laravel untuk mengurus token & kirim email otomatis
        $status = Password::sendResetLink($request->only('email'));
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password sudah dikirim ke email kamu!');
        }

        return back()->withErrors(['email' => 'Email admin tidak ditemukan.']);
    }

<<<<<<< HEAD
    // 5. Tampilkan Form Input Password Baru (Dipanggil dari link email)
    public function showResetPasswordForm($token, Request $request)
    {
        // Oper token dan email ke blade reset
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // 6. Proses Update Password Baru ke Database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Eksekusi reset password menggunakan broker 'admins'
        $status = Password::broker('admins')->reset([
            'token' => $request->token,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ], function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password) // Meng-hash password baru
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('status', 'Password berhasil diubah! Silakan login.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    // 7. Proses Logout Admin
=======
    // 5. Proses Logout Admin
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
<<<<<<< HEAD
        return redirect()->route('admin.login');
=======
        return redirect('/rahasia-admin-login');
>>>>>>> e81a9824da7f0bcb2b495d2eebe5295f4f295424
    }
}