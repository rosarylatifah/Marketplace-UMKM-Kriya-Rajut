<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| Alur PBO - Routing Architecture:
| Menjadi fasilitator atau jembatan yang memetakan HTTP Request Method 
| (GET, POST, PUT, DELETE) menuju ke Controller Object dan Method/Function 
| spesifik yang menangani enkapsulasi logika bisnis sistem.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Tampilan Pembeli (Public / Guest)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProdukController::class, 'home']);
Route::view('/about', 'pembeli.about');
Route::view('/hubungi', 'pembeli.hubungi');
Route::view('/FAQ', 'pembeli.FAQ');
Route::view('/panduan', 'pembeli.panduan');

// FR-09 & FR-10: Katalog & Fitur Pencarian Produk
Route::get('/katalog/{category?}', [ProdukController::class, 'katalog'])->name('katalog');

// FR-10: Pelanggan dapat melihat detail produk berdasarkan ID objek produk
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

// FR-11: Manajemen State Keranjang Belanja Pelanggan (Session-based)
// FIX: Nama route disesuaikan menjadi 'keranjang' agar sinkron dengan layout pembeli
Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang'); 
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// Tracking & Status Pembelian
Route::view('/lacak-pesanan', 'pembeli.lacak');
// FIX: Diarahkan ke PesananController@statusPesanan agar sesuai dengan perbaikan internal server error
Route::get('/status-pesanan', [PesananController::class, 'statusPesanan'])->name('status.pesanan');

// FITUR BARU: Proses pembatalan pesanan dari sisi Pembeli (Berada di luar prefix admin)
Route::post('/pesanan/batalkan', [CartController::class, 'batalkanPesanan'])->name('pembeli.pesanan.batalkan');

// FR-12: Pelanggan melakukan checkout dengan mengisikan data diri
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/proses-checkout', [CartController::class, 'prosesCheckout'])->name('checkout.proses');

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
    Route::get('/lihat-semua', [PesananController::class, 'lihatSemua'])->name('admin.lihat_semua');
    
    // Sisi Admin: Operasi CRUD Kelola Produk Induk & Objek Variasinya
    Route::get('/produk-list', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/produk-tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/produk-simpan', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/produk-edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/produk-update/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

    // FR-07: Admin melihat daftar pesanan aktif dan mengubah nilainya (State Mutation)
    Route::get('/pesanan-masuk', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::put('/pesanan-update/{id}', [PesananController::class, 'update'])->name('pesanan.update');
    
    // Sisi Admin: Riwayat Pesanan yang Berhasil Selesai
    Route::get('/pesanan-selesai', [PesananController::class, 'selesai'])->name('admin.pesanan.selesai');
    
    // FR-08: Admin dapat melihat data pesanan yang dibatalkan oleh pelanggan
    Route::get('/pesanan-batal', [PesananController::class, 'dibatalkan'])->name('admin.pesanan.batal');
    
    // Sisi Admin: Penghapusan Objek Pesanan Permanen dari DB
    Route::delete('/hapus-pesanan/{id}', [PesananController::class, 'destroy'])->name('pesanan.hapus');

});

// Utilitas Developer untuk pembersihan state session lokal
Route::get('/clear-session', function () {
    session()->flush();
    return "Session sudah bersih! Silakan balik ke Katalog.";
});