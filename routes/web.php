<?php

use App\Http\Controllers\Admin\Clients\ClientController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Licenses\LicenseController;
use App\Http\Controllers\Admin\Licenses\LicenseLinkController;
use App\Http\Controllers\Admin\Locations\LocationController;
use App\Http\Controllers\Admin\Locations\LocationTypeController;
use App\Http\Controllers\Admin\Lockers\LockerController;
use App\Http\Controllers\Admin\Lockers\LockerSlotController;
use App\Http\Controllers\Admin\Settings\AppSettingController;
use App\Http\Controllers\Admin\Settings\ProfileSettingController;
use App\Http\Controllers\Admin\Users\UserController;
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

    Route::resource('licenses/link', LicenseLinkController::class)
        ->except(['index', 'show'])
        ->names([
            'create' => 'admin.licenses.link.create',
            'store' => 'admin.licenses.link.store',
            'show' => 'admin.licenses.link.show',
            'edit' => 'admin.licenses.link.edit',
            'update' => 'admin.licenses.link.update',
            'destroy' => 'admin.licenses.link.destroy',
        ]);
    Route::resource('licenses', LicenseController::class)
        ->only(['index'])
        ->names([
            'index' => 'admin.licenses.index',
        ]);

    Route::group(['middleware' => ['auth'], 'prefix' => 'lockers/{locker}'], function () {
        Route::put('slots/bulk-update', [LockerSlotController::class, 'bulkUpdate'])
            ->name('admin.lockers.slots.bulkUpdate');
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

    Route::group(['middleware' => ['auth'], 'prefix' => 'location'], function () {
        $name = 'admin.location.locations';
        Route::redirect('/', 'location/locations');
        Route::resource('locations', LocationController::class, [
            'names' => [
                'index' => $name.'.index',
                'create' => $name.'.create',
                'store' => $name.'.store',
                'show' => $name.'.show',
                'edit' => $name.'.edit',
                'update' => $name.'.update',
                'destroy' => $name.'.destroy',
            ],
        ]);
        Route::resource('types', LocationTypeController::class, [
            'names' => [
                'index' => 'admin.location.types.index',
                'create' => 'admin.location.types.create',
                'store' => 'admin.location.types.store',
                'show' => 'admin.location.types.show',
                'edit' => 'admin.location.types.edit',
                'update' => 'admin.location.types.update',
                'destroy' => 'admin.location.types.destroy',
            ],
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

    Route::resource('profileSettings', ProfileSettingController::class)
        ->only(['index', 'edit', 'update'])
        ->names([
            'index' => 'admin.profileSettings.index',
            'edit' => 'admin.profileSettings.edit',
            'update' => 'admin.profileSettings.update',
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
