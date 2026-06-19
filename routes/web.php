<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\KategoriController;

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
Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang'); 
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// Tracking & Status Pembelian
Route::view('/lacak-pesanan', 'pembeli.lacak');
Route::get('/status-pesanan', [PesananController::class, 'statusPesanan'])->name('status.pesanan');


// Proses pembatalan pesanan dari sisi Pembeli
Route::post('/pesanan/ajukan-batal', [CartController::class, 'ajukanPembatalan'])->name('pembeli.pesanan.ajukanBatal');
// FR-12: Pelanggan melakukan checkout dengan mengisikan data diri
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/proses-checkout', [CartController::class, 'prosesCheckout'])->name('checkout.proses');

Route::view('/pembayaran', 'pembeli.pembayaran');
Route::view('/berhasil', 'pembeli.berhasil');

Route::post('/upload-bukti', [PesananController::class, 'uploadBukti'])->name('bukti.upload');
Route::post('/konfirmasi-pesanan/{id}', [App\Http\Controllers\PesananController::class, 'konfirmasiDiterima'])->name('pesanan.konfirmasi');


/*
|--------------------------------------------------------------------------
| 🔐 Tampilan Admin (Autentikasi - URL Baru & Berdiri Sendiri)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    // Tampilan & Proses Login Admin
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    // FIX: Menambahkan alias 'login' agar middleware 'auth' Laravel tidak melempar error 404 saat redirect
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

    // Tampilan & Proses Kirim Link Lupa Password
    Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('admin.password.email');
});

// Proses Logout Admin
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| 🛡️ Tampilan Admin (Operasional Panel) - Proteksi Middleware Auth & Prefix admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
Route::post('/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
    Route::get('/lihat-semua', [PesananController::class, 'lihatSemua'])->name('admin.lihat_semua');
    
    // Sisi Admin: Operasi CRUD Kelola Produk
    Route::get('/produk-list', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/produk-tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/produk-simpan', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/produk-edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/produk-update/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');
    
    // PERBAIKAN DI SINI: Diubah menjadi Route::delete untuk menangani bulk-hapus
    Route::delete('/produk-bulk-hapus', [ProdukController::class, 'bulkDestroy'])->name('admin.produk.bulkDestroy');

    // FR-07: Admin melihat daftar pesanan masuk/aktif dan mengubah nilainya
    Route::get('/pesanan-masuk', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('/pesanan-konfirmasi', [PesananController::class, 'konfirmasi'])->name('admin.pesanan.konfirmasi');
    
    // 🌟 UTAMA: Route update status yang dipanggil oleh dropdown halaman konfirmasi & pesanan masuk
    Route::put('/pesanan-update/{id}', [PesananController::class, 'update'])->name('pesanan.update');
    
    // Sisi Admin: Riwayat Pesanan yang Berhasil Selesai
    Route::get('/pesanan-selesai', [PesananController::class, 'selesai'])->name('admin.pesanan.selesai');
    
    // FR-08: Admin dapat melihat data pesanan refund / batal
    Route::get('/pesanan-batal', [PesananController::class, 'dibatalkan'])->name('admin.pesanan.batal');
    Route::get('/pesanan-pengajuan-batal', [PesananController::class, 'pengajuanBatal'])->name('admin.pesanan.pengajuanBatal');
Route::put('/pesanan-batal/setujui/{id}', [PesananController::class, 'setujuiPembatalan'])->name('admin.pesanan.setujuiBatal');
Route::put('/pesanan-batal/tolak/{id}', [PesananController::class, 'tolakPembatalan'])->name('admin.pesanan.tolakBatal');

    
    // Sisi Admin: Penghapusan Objek Pesanan Permanen dari DB
    Route::delete('/hapus-pesanan/{id}', [PesananController::class, 'destroy'])->name('pesanan.hapus');
});


/*
|--------------------------------------------------------------------------
| Utilitas Developer
|--------------------------------------------------------------------------
*/
Route::get('/clear-session', function () {
    session()->flush();
    return "Session sudah bersih! Silakan balik ke Katalog.";
});