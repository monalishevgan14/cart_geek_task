<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::redirect('/', '/list-product');

Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
Route::get('/list-product', [ProductController::class, 'index'])->name('products.index');

Route::get('/edit/{id}', [ProductController::class, 'edit']);
Route::post('/update/{id}', [ProductController::class, 'update']);
Route::delete('/delete-image/{id}', [ProductController::class, 'deleteImage']);

Route::post('/store', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'list']);
Route::delete('/delete/{id}', [ProductController::class, 'destroy']);