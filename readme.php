<?php

/**
 * README
 * File ini hanya berisi dokumentasi atau catatan penting terkait proyek ini.
 * 
 * << cara menggunakan Starter Data (seeder) >>
 * php artisan migrate:fresh
 * php artisan db:seed
 * 
 * << cara menjalankan cron job tiruan laravel>>
 * php artisan schedule:work
 * 
 * << cara menjalankan queue email laravel >>
 * php artisan queue:work --queue=email
 * 
 * << Route >>
 * routes\web.php
 * 
 * << Task Scheduling >>
 * routes\console.php
 * 
 * << Custom Artisan Command >>
 * app\Console\Commands
 * 
 * << Config >>
 * config\app.php
 * config\database.php
 * config\adminlte.php
 * config\settings.php (spatie/larevel-settings)
 * 
 * << Event >>
 * app\Events\UserSensitiveDataChanged.php
 * 
 * << Controller Admin >>
 * app\Http\Controllers\Admin\Billboard\BillboardController.php
 * app\Http\Controllers\Admin\Billboard\BillboardSewaController.php
 * app\Http\Controllers\Admin\User\UserListController.php
 * app\Http\Controllers\Admin\DashboardController.php
 * app\Http\Controllers\Admin\StatistikController.php
 * app\Http\Controllers\Admin\Setting\SettingReminderController.php
 * 
 * << Controller Klien >>
 * app\Http\Controllers\Klien\Billboard\BillboardController.php
 * app\Http\Controllers\Klien\Billboard\BillboardSewaController.php
 * app\Http\Controllers\Klien\DashboardController.php
 * 
 * << Controller Helpers >>
 * app\Http\Controllers\Helpers\ControllerHelpers.php
 * 
 * << Controller Global >>
 * app\Http\Controllers\ProfilController.php
 * 
 * << Form Request Validasi >>
 * app\Http\Requests\AddUserRequest.php
 * app\Http\Requests\UpdateUserRequest.php
 * app\Http\Requests\UpdateProfilAkunRequest.php
 * app\Http\Requests\UpdateProfilInfoRequest.php
 * app\Http\Requests\AddUpdateBillboardRequest.php
 * 
 * << Listener >>
 * app\Listeners\LogUserSensitiveDataChange.php
 * 
 * << Model >>
 * app\Models\Profile.php
 * app\Models\User.php
 * app\Models\Billboard.php
 * app\Models\BillboardSewa.php
 * 
 * << Trait (Helper) >>
 * app\Traits\HandlersException.php
 * app\Traits\ServiceLogger.php
 * 
 * << Javascript >>
 * public\page\beranda.min.js
 * public\page\custom_form.min.js
 * public\page\custom_format.min.js
 * public\page\custom_table.min.js
 * public\page\profil.min.js
 * public\page\admin\billboard-list.min.js
 * public\page\admin\billboard-sewa.min.js
 * public\page\admin\user-list.min.js
 * public\page\admin\setting-reminder.min.js
 * public\page\klien\billboard-list.min.js
 * public\page\klien\billboard-sewa.min.js
 * 
 * << CSS >>
 * public\page\custom.min.css
 * 
 * << View >>
 * resources\views\layouts\app.blade.php (Main Layout)
 * resources\views\admin
 * resources\views\klien
 * resources\views\errors (Error Pages)
 * resources\views\profil.blade.php
 * resources\views\emails (Email Layout)
 * 
 * << Migration >>
 * database\migrations
 * 
 * << Seeder Database >>
 * database\seeders
 * 
 * << Factory Database >>
 * database\factories
 * 
 * << File Bahasa Pesan Validasi >>
 * lang
 * 
 * 
 */