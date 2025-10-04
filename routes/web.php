<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/klien', [App\Http\Controllers\HomeController::class, 'index']);

Route::middleware(['auth'])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/profile', 'userProfile')->name('user.profile');
        Route::put('/user-profile', 'updateProfile')->name('profile.update');
});

Route::middleware(['auth'])
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/admin/user-list', 'userList')->name('user.list');
        Route::get('/admin/user-datatable', 'datatable')->name('user.datatable');
        Route::post('/admin/users', 'addUser')->name('user.add');
        Route::get('/admin/users/{id}', 'getUserById')->name('user.get');
        Route::put('/admin/users/{id}', 'updateUser')->name('user.update');

        Route::get('/statistic/user', 'statistic')->name('statistic.user');
    });