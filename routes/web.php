<?php

use Illuminate\Support\Facades\Route;
// Panggil Controller yang benar (Tanpa .php)
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Tampilan Pembeli (Public)
|--------------------------------------------------------------------------
*/
// Route '/' cuma boleh ada satu, kita pakai yang ke pembeli.home
Route::view('/', 'pembeli.home');
Route::view('/katalog', 'pembeli.katalog');
Route::view('/about', 'pembeli.about');
Route::view('/hubungi', 'pembeli.hubungi');
Route::view('/lacak-pesanan', 'pembeli.lacak');
Route::view('/status-pesanan', 'pembeli.status');

/*
|--------------------------------------------------------------------------
| Tampilan Admin (Penjual)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::view('/login', 'admin.login');
    
    // Pakai Controller untuk dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::view('/produk', 'admin.produk');
    Route::view('/kategori', 'admin.kategori');
    Route::view('/pesanan', 'admin.pesanan');
    
    // Note: Di dalam prefix 'admin', rutenya otomatis jadi /admin/produk dll.
    // Jadi tidak perlu menulis /admin/produk lagi di dalamnya.
});