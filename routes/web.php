<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\User\UserListController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/admin', [HomeController::class, 'index']);
// Route::get('/klien', [HomeController::class, 'index']);

Route::middleware(['auth'])
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/profile', 'userProfile')->name('user.profile');
        Route::put('/user-profile', 'updateProfile')->name('profile.update');
});

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(
        function () {

            Route::get('/', [DashboardController::class, 'index']);

            Route::prefix('statistik')
                ->group(function () {
                    Route::get('/user-list', [StatistikController::class, 'userList']);
                    Route::get('/user-login', [StatistikController::class, 'userLogin']);
                });

            Route::prefix('user')
                ->group(function () {

                    Route::prefix('user-list')
                        ->group(function () {
                            Route::get('/', [UserListController::class, 'index']);
                            Route::post('/tabel', [UserListController::class, 'tabel']);
                        });

                });

    });


Route::middleware(['auth'])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/admin/user-list', 'userList')->name('user.list');
        Route::get('/admin/user-datatable', 'datatable')->name('user.datatable');
        Route::post('/admin/users', 'addUser')->name('user.add');
        Route::get('/admin/users/{id}', 'getUserById')->name('user.get');
        Route::put('/admin/users/{id}', 'updateUser')->name('user.update');

        Route::get('/statistic/user', 'statistic')->name('statistic.user');
    });