<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;

// Halaman-halaman utama
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/katalog/{category?}', [CartController::class, 'katalog'])->name('katalog');
// Logic untuk manipulasi keranjang
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

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
Route::view('/keranjang', 'pembeli.keranjang');
Route::view('/checkout', 'pembeli.checkout');
Route::view('/pembayaran', 'pembeli.pembayaran');
Route::view('/berhasil', 'pembeli.berhasil');
Route::get('/katalog/{kategori?}', [KatalogController::class, 'index'])->name('katalog');
Route::get('/keranjang', function () { return view('pembeli.keranjang'); })->name('keranjang');


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