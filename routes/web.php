<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ADMIN ONLY
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    });

    // ADMIN + STOREKEEPER
    Route::middleware('role:admin,storekeeper')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('purchases', PurchaseController::class);
        Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    });

    // ADMIN + CASHIER
    Route::middleware('role:admin,cashier')->group(function () {
        Route::resource('sales', SaleController::class);
    });
});

require __DIR__.'/auth.php';