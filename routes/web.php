<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\MailProductsToStaffController;
use App\Http\Controllers\ProductsImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');

    Route::post('/imports/products', [ProductsImportController::class, 'import'])->name('imports.products');
    Route::post('/exports/products/mail', [MailProductsToStaffController::class, 'mail'])->name('exports.products.mail');
});
