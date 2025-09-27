<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/user-list', [App\Http\Controllers\UserController::class, 'userList'])->name('user.list');

Route::middleware(['auth'])
    ->controller(AdminController::class)
    ->group(function () {
        Route::post('/admin/users', 'addUser')->name('user.add');
    });