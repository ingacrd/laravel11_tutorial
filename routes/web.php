<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::view('/', 'welcome');
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->can('update', 'post')->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');
    Route::middleware('is-admin')->group(function(){
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::get('/admin/post/{post}/edit', [AdminPostController::class, 'edit'])->name('admin.posts.edit');
        Route::put('/admin/post/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
        Route::delete('/admin/post/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
    });


    //
//    Route::get('/admin', function (){
//        return 'You are logged in as an admin';
//    })->middleware('can:is-admin')->name('admin');
//
//    Route::get('/admin', function (){
//        return 'You are logged in as an admin';
//    })->middleware('is-admin')->name('admin');
});

// //Route::resource('/posts', PostController::class)->middleware('auth');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
//Route::get('/posts/{post}', [PostController::class, 'show'])->middleware('can-view-post')->name('posts.show');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
//Route::resource('/posts', PostController::class);

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'register'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginUserController::class, 'login'])->name('login');
    Route::post('/login', [LoginUserController::class, 'store'])->name('login.store');

});
