<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- AUTHENTICATION ---
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PROTECTED ROUTES (Harus Login) ---
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Data Master
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Transaksi & Laporan
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update', 'destroy']); // Index untuk Laporan
    Route::resource('sales', SaleController::class)->except(['edit', 'update', 'destroy']); // Index untuk Laporan

    // Inventory
    Route::resource('adjustments', StockAdjustmentController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/stock-card', [ReportController::class, 'stockCard'])->name('stock-card.index');
    Route::get('/min-stock', [ReportController::class, 'minStock'])->name('min-stock.index');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

});
