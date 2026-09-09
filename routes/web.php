<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDashboardController;
use App\Http\Controllers\SizeController;


Route::redirect('/', '/product-dashboard');

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

Route::get('/category', [
    CategoryController::class,
    'index'
])->name('category.index');

Route::get('/category/create', [
    CategoryController::class,
    'create'
])->name('category.create');

Route::post('/category', [
    CategoryController::class,
    'store'
])->name('category.store');

Route::get('/category/{category}/edit', [
    CategoryController::class,
    'edit'
])->name('category.edit');

Route::put('/category/{category}', [
    CategoryController::class,
    'update'
])->name('category.update');

Route::delete('/category/{category}', [
    CategoryController::class,
    'destroy'
])->name('category.destroy');


/*
|--------------------------------------------------------------------------
| Product Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/product-dashboard', [
    ProductDashboardController::class,
    'index'
])->name('product.dashboard');


/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::get('/product', [
    ProductController::class,
    'index'
])->name('product.index');

Route::get('/product/create', [
    ProductController::class,
    'create'
])->name('product.create');

Route::post('/product', [
    ProductController::class,
    'store'
])->name('product.store');

Route::get('/product/{product}/edit', [
    ProductController::class,
    'edit'
])->name('product.edit');

Route::put('/product/{product}', [
    ProductController::class,
    'update'
])->name('product.update');

Route::delete('/product/{product}', [
    ProductController::class,
    'destroy'
])->name('product.destroy');


/*
|--------------------------------------------------------------------------
| NEW PRODUCT FEATURES
|--------------------------------------------------------------------------
*/

/*
| Bulk delete
*/
Route::delete('/products/bulk-delete', [
    ProductController::class,
    'bulkDestroy'
])->name('products.bulk-delete');


/*
| Export CSV
*/
Route::get('/products/export', [
    ProductController::class,
    'export'
])->name('products.export');


/*
| Duplicate product
*/
Route::post('/product/{product}/duplicate', [
    ProductController::class,
    'duplicate'
])->name('product.duplicate');


/*
|--------------------------------------------------------------------------
| Size Routes
|--------------------------------------------------------------------------
*/

Route::get('/size', [
    SizeController::class,
    'index'
])->name('size.index');

Route::get('/size/create', [
    SizeController::class,
    'create'
])->name('size.create');

Route::post('/size', [
    SizeController::class,
    'store'
])->name('size.store');

Route::get('/size/{size}/edit', [
    SizeController::class,
    'edit'
])->name('size.edit');

Route::put('/size/{size}', [
    SizeController::class,
    'update'
])->name('size.update');

Route::delete('/size/{size}', [
    SizeController::class,
    'destroy'
])->name('size.destroy');
