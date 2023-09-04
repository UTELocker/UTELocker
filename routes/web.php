<?php

use App\Http\Controllers\Admin\Clients\ClientController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Lockers\LockerController;
use App\Http\Controllers\Admin\Lockers\LockerSlotController;
use App\Http\Controllers\Admin\Settings\AppSettingController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('clients', ClientController::class)
        ->names([
            'index' => 'admin.clients.index',
            'create' => 'admin.clients.create',
            'store' => 'admin.clients.store',
            'show' => 'admin.clients.show',
            'edit' => 'admin.clients.edit',
            'update' => 'admin.clients.update',
            'destroy' => 'admin.clients.destroy',
        ]);

    Route::resource('lockers', LockerController::class)
        ->names([
            'index' => 'admin.lockers.index',
            'create' => 'admin.lockers.create',
            'store' => 'admin.lockers.store',
            'show' => 'admin.lockers.show',
            'edit' => 'admin.lockers.edit',
            'update' => 'admin.lockers.update',
            'destroy' => 'admin.lockers.destroy',
        ]);

    Route::group(['middleware' => ['auth'], 'prefix' => 'lockers/{locker}'], function () {
        Route::resource('slots', LockerSlotController::class)
            ->names([
                'index' => 'admin.lockers.slots.index',
                'create' => 'admin.lockers.slots.create',
                'store' => 'admin.lockers.slots.store',
                'show' => 'admin.lockers.slots.show',
                'edit' => 'admin.lockers.slots.edit',
                'update' => 'admin.lockers.slots.update',
                'destroy' => 'admin.lockers.slots.destroy',
            ]);
    });

    Route::get(
        'settings/change-language',
        [AppSettingController::class, 'changeLanguage']
    )->name('admin.settings.change-language');
    Route::resource('settings', AppSettingController::class)
        ->only(['index', 'edit', 'update', 'change-language'])
        ->names([
            'index' => 'admin.settings.index',
            'edit' => 'admin.settings.edit',
            'update' => 'admin.settings.update',
        ]);
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', UserController::class)
        ->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);
});

require __DIR__.'/auth.php';
