<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Klien\DashboardController as KlienDashboardController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\User\UserListController;
use App\Http\Controllers\Admin\Billboard\BillboardController;
use App\Http\Controllers\admin\billboard\BillboardSewaController;
use App\Http\Controllers\Klien\Billboard\BillboardController as KlienBillboardController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect By Role
Route::get('/billboard', function () {
    $userRole = Auth::user()->getRoleNames()->first();
    return $userRole === 'Admin'
        ? redirect()->route('admin.billboard.index')
        : redirect()->route('klien.billboard.index');
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

            // admin -> user
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
                            Route::get('/get-email/{email}', [UserListController::class, 'getEmail'])
                                ->name('admin.user.list.getEmail'); 
                            Route::get('/opsi-filter', [UserListController::class, 'opsiFilter'])
                                ->name('admin.user.list.opsi.filter');
                            Route::post('/simpan', [UserListController::class, 'simpan'])
                                ->name('admin.user.list.simpan'); 
                            Route::put('/update/{id}', [UserListController::class, 'update'])
                                ->name('admin.user.list.update'); 
                        });

                });

            // admin -> billboard
            Route::prefix('billboard')
                ->group(function () {

                    Route::prefix('billboard-list')
                        ->group(function () {
                            Route::get('/', [BillboardController::class, 'index'])
                                ->name('admin.billboard.index');
                            Route::get('/tabel', [BillboardController::class, 'tabel'])
                                ->name('admin.billboard.list.tabel');
                            Route::get('/get-id/{id}', [BillboardController::class, 'getId'])
                                ->name('admin.billboard.list.getId'); 
                            Route::get('/opsi-filter', [BillboardController::class, 'opsiFilter'])
                                ->name('admin.billboard.list.opsi.filter');
                            Route::post('/simpan', [BillboardController::class, 'simpan'])
                                ->name('admin.billboard.list.simpan'); 
                            Route::put('/update/{id}', [BillboardController::class, 'update'])
                                ->name('admin.billboard.list.update'); 
                            Route::post('/update-gambar/{id}', [BillboardController::class, 'updateGambar'])
                                ->name('admin.billboard.list.update.gambar'); 
                        });

                    Route:: prefix('billboard-sewa')
                        ->group(function () {
                            Route::get('/', [BillboardSewaController::class, 'index'])
                                ->name('admin.billboard.sewa.tabel');
                            Route::post('/simpan', [BillboardSewaController::class, 'simpan'])
                                ->name('admin.billboard.sewa.simpan'); 
                        });

                });

    });

// Role klien
Route::prefix('klien')
    ->middleware(['auth', 'role:Klien'])
    ->group(function (){
        
        // klien dashboard
        Route::get('/', [KlienDashboardController::class, 'index'])
            ->name('klien.index');

        // klien -> billboard
        Route::prefix('billboard')
            ->group(function () {

                Route::prefix('billboard-list')
                    ->group(function () {
                        Route::get('/', [KlienBillboardController::class, 'index'])
                            ->name('klien.billboard.index');
                        Route::get('/tabel', [KlienBillboardController::class, 'tabel'])
                            ->name('klien.billboard.list.tabel');
                        Route::get('/opsi-filter', [BillboardController::class, 'opsiFilter'])
                            ->name('klien.billboard.list.opsi.filter');
                    });

            });

    });

// Role umum
Route::prefix('profil')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ProfilController::class, 'index'])
            ->name('profil.index');
        Route::put('/update-akun/{userId}', [ProfilController::class, 'updateAkun'])
            ->name('profil.update.akun');
        Route::put('/update-info/{userId}', [ProfilController::class, 'updateInfo'])
            ->name('profil.update.info');
    });

