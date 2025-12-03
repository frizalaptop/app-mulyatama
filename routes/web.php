<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Klien\DashboardController as KlienDashboardController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\User\UserListController;
use App\Http\Controllers\Admin\Billboard\BillboardController;
use App\Http\Controllers\admin\billboard\BillboardSewaController;
use App\Http\Controllers\Admin\Setting\SettingReminderController;
use App\Http\Controllers\Klien\Billboard\BillboardController as KlienBillboardController;
use App\Http\Controllers\Klien\Billboard\BillboardSewaController as KlienBillboardSewaController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $userRole = Auth::user()->getRoleNames()->first();
        return $userRole === 'Admin'
            ? redirect()->route('admin')
            : redirect()->route('klien');
    });
    
    // Global route billboard list
    Route::get('/billboard-list', function () {
        $userRole = Auth::user()->getRoleNames()->first();
        return $userRole === 'Admin'
            ? redirect()->route('admin.billboard.list')
            : redirect()->route('klien.billboard.list');
    });
    
    // Global route billboard sewa
    Route::get('/billboard-sewa', function () {
        $userRole = Auth::user()->getRoleNames()->first();
        return $userRole === 'Admin'
            ? redirect()->route('admin.billboard.sewa')
            : redirect()->route('klien.billboard.sewa');
    });
});

// Role admin 
Route::prefix('admin')
    ->middleware(['auth', 'role:Admin'])
    ->group(
        function () {

            // admin dashboard
            Route::get('/', [DashboardController::class, 'index'])
                ->name('admin');

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
                                ->name('admin.billboard.list');
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
                                ->name('admin.billboard.sewa');
                            Route::get('/tabel', [BillboardSewaController::class, 'tabel'])
                                ->name('admin.billboard.sewa.tabel');
                            Route::get('/opsi-filter', [BillboardSewaController::class, 'opsiFilter'])
                                ->name('admin.billboard.sewa.opsi.filter');
                            Route::post('/simpan', [BillboardSewaController::class, 'simpan'])
                                ->name('admin.billboard.sewa.simpan'); 
                        });

                });
            
            // admin -> setting
            Route::prefix('setting')
                ->group(function () {

                    Route::prefix('reminder')
                        ->group(function () {
                            Route::get('/', [SettingReminderController::class, 'index'])
                                ->name('admin.setting.reminder');
                            Route::get('/tabel', [SettingReminderController::class, 'tabel'])
                                ->name('admin.setting.reminder.tabel');
                            Route::get('/get-id/{id}', [SettingReminderController::class, 'getId'])
                                ->name('admin.setting.reminder.getId');
                            Route::get('/opsi-filter', [SettingReminderController::class, 'opsiFilter'])
                                ->name('admin.setting.reminder.filter');
                            Route::put('/update/{id}', [SettingReminderController::class, 'update'])
                                ->name('admin.setting.reminder.update');
                        });

                });

    });

// Role klien
Route::prefix('klien')
    ->middleware(['auth', 'role:Klien'])
    ->group(function (){
        
        // klien dashboard
        Route::get('/', [KlienDashboardController::class, 'index'])
            ->name('klien');

        // klien -> billboard
        Route::prefix('billboard')
            ->group(function () {

                Route::prefix('billboard-list')
                    ->group(function () {
                        Route::get('/', [KlienBillboardController::class, 'index'])
                            ->name('klien.billboard.list');
                        Route::get('/tabel', [KlienBillboardController::class, 'tabel'])
                            ->name('klien.billboard.list.tabel');
                        Route::get('/opsi-filter', [BillboardController::class, 'opsiFilter'])
                            ->name('klien.billboard.list.opsi.filter');
                    });

                Route:: prefix('billboard-sewa')
                    ->group(function () {
                        Route::get('/', [KlienBillboardSewaController::class, 'index'])
                            ->name('klien.billboard.sewa');
                        Route::get('/tabel', [KlienBillboardSewaController::class, 'tabel'])
                            ->name('klien.billboard.sewa.tabel');
                        Route::get('/opsi-filter', [KlienBillboardSewaController::class, 'opsiFilter'])
                            ->name('klien.billboard.sewa.opsi.filter');
                        Route::post('/simpan', [KlienBillboardSewaController::class, 'simpan'])
                            ->name('klien.billboard.sewa.simpan'); 
                    });

            });

    });

// Role umum
Route::prefix('profil')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ProfilController::class, 'index'])
            ->name('profil');
        Route::put('/update-akun/{userId}', [ProfilController::class, 'updateAkun'])
            ->name('profil.update.akun');
        Route::put('/update-info/{userId}', [ProfilController::class, 'updateInfo'])
            ->name('profil.update.info');
    });

