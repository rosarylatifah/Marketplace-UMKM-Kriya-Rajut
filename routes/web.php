<?php

use Illuminate\Support\Facades\Route;
// 1. Panggil Controller-nya di sini
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// 2. Tambahin route buat ke halaman Dashboard
Route::get('/dashboard', [DashboardController.php, 'index']);
