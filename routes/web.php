<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;


// Route::get('/', function () {
//     return view('welcome');
// });

// custome Facades
use App\Facades\Test;
Route::get('/faced', function () {
    return Test::hello();
});

// checking group middleware
// Route::view('/home', 'home')->name('home')->middleware('check');

//custome middleware
Route::middleware('check')->group(function () {
    Route::view('/home', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
}); 


/*
|--------------------------------------------------------------------------
| login and registration (public url)
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')
                     ->with('success', 'Logged out successfully');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:user'])->group(function(){

    // Route::redirect('/', '/list-product');

    Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
    Route::get('/myproduct', [ProductController::class, 'index'])->name('products.index');
    Route::get('/edit/{id}', [ProductController::class, 'edit']);
    Route::post('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete-image/{id}', [ProductController::class, 'deleteImage']);


    Route::get('/products', [ProductController::class, 'list']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);


    // =================csrf token==============================
    Route::get('/token', function (Request $request) {
        $token = $request->session()->token();
    
        $token = csrf_token();
    
        // ...
    });

    // csrf token in form
    Route::view('/contact', 'contact');

    Route::post('/contact-submit', function (Request $request) {

        return "Form Submitted Successfully";

    });

    // exception handling
    Route::get('/exception', function () {
        abort(404, 'Page not found!');
        throw new Exception('This is a custom exception!');
    });

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('/admin/products', [AdminController::class, 'index'])->name('adminProducts.index');
    Route::get('/admin/products/list', [AdminController::class, 'list'])->name('adminProducts.list');


});



// important youtube tutorial for login
// https://www.youtube.com/watch?v=x4uJjU5tLAA&t=168s