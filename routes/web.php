<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| Tampilan Pembeli (Public)
|--------------------------------------------------------------------------
*/

Route::view('/', 'pembeli.home');
Route::view('/about', 'pembeli.about');
Route::view('/hubungi', 'pembeli.hubungi');
Route::view('/FAQ', 'pembeli.FAQ');
Route::view('/panduan', 'pembeli.panduan');

// Mengikuti struktur temen lu (Katalog & Keranjang di CartController)
Route::get('/katalog/{category?}', [CartController::class, 'katalog'])->name('katalog');
Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang');
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// Tracking & Status
Route::view('/lacak-pesanan', 'pembeli.lacak');
Route::view('/status-pesanan', 'pembeli.status');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::view('/pembayaran', 'pembeli.pembayaran');
Route::view('/berhasil', 'pembeli.berhasil');

/*
|--------------------------------------------------------------------------
| Tampilan Admin (Penjual)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    
    Route::view('/login', 'admin.login');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::view('/kategori', 'admin.kategori');
    
    // Kelola Produk (Kodingan Lu)
    Route::get('/produk-list', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/produk-tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/produk-simpan', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/produk-edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/produk-update/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

    // Kelola Pesanan
    Route::get('/pesanan-masuk', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('/pesanan-selesai', [PesananController::class, 'selesai'])->name('admin.pesanan.selesai');
    Route::put('/pesanan-update/{id}', [PesananController::class, 'update'])->name('pesanan.update');
    Route::delete('/hapus-pesanan/{id}', [PesananController::class, 'destroy'])->name('pesanan.hapus');
    
    Route::view('/lihat-semua', 'admin.lihat_semua')->name('admin.lihat_semua');
});