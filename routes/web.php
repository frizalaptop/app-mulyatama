<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/user-list', 'userList')->name('user.list');
        Route::get('/user-profile', 'userProfile')->name('user.profile');
        Route::put('/user-profile', 'updateProfile')->name('profile.update');
});

Route::middleware(['auth'])
    ->controller(AdminController::class)
    ->group(function () {
        Route::post('/admin/users', 'addUser')->name('user.add');
        Route::put('/admin/users/{id}', 'updateUser')->name('user.update');
    });