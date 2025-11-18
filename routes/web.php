<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController; // Import AuthController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- AUTHENTICATION ---
Route::get('/', function () {
    return redirect()->route('login'); // Redirect awal ke login
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PROTECTED ROUTES (Harus Login) ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // 1. Data Master
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // 2. Transaksi
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);

    // 3. Inventory
    Route::resource('adjustments', StockAdjustmentController::class);
    Route::get('/stock-card', [ReportController::class, 'stockCard'])->name('stock-card.index');
    Route::get('/min-stock', [ReportController::class, 'minStock'])->name('min-stock.index');

    // 4. Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

});
