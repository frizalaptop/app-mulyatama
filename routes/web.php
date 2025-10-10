<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Klien\DashboardController as KlienDashboardController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\User\UserListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Auth::routes();

// Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])
    ->controller(ProfileController::class)
    ->group(function () {
        Route::get('/profile', 'userProfile')->name('user.profile');
        Route::put('/user-profile', 'updateProfile')->name('profile.update');
});

// Role admin 
Route::prefix('admin')
    ->middleware(['auth', 'role:Admin'])
    ->group(
        function () {

            // admin dashboard
            Route::get('/', [DashboardController::class, 'index'])
                ->name('admin.index');

            // admin -> statistik json
            Route::prefix('statistik')
                ->group(function () {

                    Route::get('/user-list', [StatistikController::class, 'userList'])
                        ->name('admin.statistik.user.list');
                    Route::get('/user-login', [StatistikController::class, 'userLogin'])
                        ->name('admin.statistik.user.login');

                });

            Route::prefix('user')
                ->group(function () {

                    Route::prefix('user-list')
                        ->group(function () {
                            Route::get('/', [UserListController::class, 'index'])
                                ->name('admin.user.list');
                            Route::get('/tabel', [UserListController::class, 'tabel'])
                                ->name('admin.user.list.tabel');
                            Route::get('/get-id/{id}', [UserListController::class, 'getId'])
                                ->name('admin.user.list.getId'); 
                            Route::post('/simpan', [UserListController::class, 'simpan'])
                                ->name('admin.user.list.simpan'); 
                            Route::put('/update/{id}', [UserListController::class, 'update'])
                                ->name('admin.user.list.update'); 
                        });

                });

    });


Route::prefix('klien')
    ->middleware(['auth', 'role:Klien'])
    ->group(function (){
        Route::get('/', [KlienDashboardController::class, 'index'])
            ->name('klien.index');

    });
