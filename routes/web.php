<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Category & Product CRUD Routes (Inertia)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |-------------------------
    | Category Routes
    |-------------------------
    */
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');

    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');

    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');

    Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');

    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');

    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');


    /*
    |-------------------------
    | Product Routes
    |-------------------------
    */
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');

    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');

    Route::post('/product', [ProductController::class, 'store'])->name('product.store');

    Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');

    Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');

    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
});

