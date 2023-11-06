<?php

use App\Http\Controllers\Admin\Bookings\BookingController;
use App\Http\Controllers\Admin\Clients\ClientController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Licenses\LicenseController;
use App\Http\Controllers\Admin\Licenses\LicenseLinkController;
use App\Http\Controllers\Admin\Locations\LocationController;
use App\Http\Controllers\Admin\Locations\LocationTypeController;
use App\Http\Controllers\Admin\Lockers\LockerController;
use App\Http\Controllers\Admin\Lockers\LockerSlotController;
use App\Http\Controllers\Admin\Payments\PaymentController;
use App\Http\Controllers\Admin\Payments\PaymentMethodController;
use App\Http\Controllers\Admin\Settings\AppSettingController;
use App\Http\Controllers\Admin\Settings\ProfileSettingController;
use App\Http\Controllers\Admin\Settings\SiteGroupSettingController;
use App\Http\Controllers\Admin\Settings\PusherSettingController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\PortalController;
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

Route::group(['middleware' => ['auth', 'auth.verify']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permissionAdmin')
        ->name('admin.dashboard');
        Route::get('portal', [PortalController::class, 'index'])->name('portal');
        Route::get('portal/{any}', [PortalController::class, 'index'])->where('any', '.*');
        Route::get('wallet', [PortalController::class, 'index'])->name('wallet');
        Route::get('wallet/transactions', [PortalController::class, 'index'])->name('wallet.transactions');
        Route::get('wallet/{any}', [PortalController::class, 'index'])->where('any', '.*');
        Route::get('help-call', [PortalController::class, 'index'])->name('portal');
        Route::get('help-call/{any}', [PortalController::class, 'index'])->where('any', '.*');
});

Route::group(['middleware' => ['auth', 'auth.verify', 'permissionAdmin']], function () {
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

    Route::group(['middleware' => ['auth'], 'prefix' => 'bookings'], function () {
        Route::get('/', [BookingController::class, 'index'])
            ->name('admin.bookings.index');
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

    Route::resource('settings/site-group', SiteGroupSettingController::class)
        ->only(['index', 'update'])
        ->names([
            'index' => 'admin.siteGroupSettings.index',
            'update' => 'admin.siteGroupSettings.update'
        ]);

    Route::get(
        'settings/profile',
        [ProfileSettingController::class, 'index']
    )->name('admin.profileSettings.index');

    Route::group(['middleware' => ['auth'], 'prefix' => 'payment'], function () {
        Route::get('/', [PaymentController::class, 'index'])
            ->name('admin.payment.index');
        Route::prefix('transactions')->group(function () {
            Route::get('/', [PaymentController::class, 'transactions'])
                ->name('admin.payment.transactions');
            Route::get('/{transaction}', [PaymentController::class, 'transaction'])
                ->name('admin.payment.transaction');
        });
        Route::resource('methods', PaymentMethodController::class)
            ->names([
                'index' => 'admin.payment.methods.index',
                'create' => 'admin.payment.methods.create',
                'store' => 'admin.payment.methods.store',
                'show' => 'admin.payment.methods.show',
                'edit' => 'admin.payment.methods.edit',
                'update' => 'admin.payment.methods.update',
                'destroy' => 'admin.payment.methods.destroy',
            ]);
    });

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

Route::get('/event', [LockerController::class, 'event'])->name('event');

require __DIR__.'/auth.php';
