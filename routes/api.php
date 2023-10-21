<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Locations\LocationsController;
use App\Http\Controllers\Api\Lockers\LockerController;
use App\Http\Controllers\Api\Bookings\BookingController;
use App\Http\Controllers\Api\Notifications\NotificationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::get('/list-client', [UserController::class, 'getListClient'])->name('api.user.listClient');
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('signUp', [AuthController::class, 'signUp'])->name('api.auth.signUp');
    Route::middleware('auth:sanctum')
        ->get('logout', [AuthController::class, 'logout'])
        ->name('api.auth.logout');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'get'])->name('api.user.get');
        Route::post('/', [UserController::class, 'update'])->name('api.user.update');
    });

    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationsController::class, 'get'])->name('api.location.get');
    });

    Route::prefix('lockers')->group(function () {
        Route::get('/', [LockerController::class, 'get'])->name('api.locker.get');
        Route::post('/{id}/modules', [LockerController::class, 'getModules'])->name('api.locker.getModules');
    });

    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'getBookingActivities'])->name('api.bookings.getBookingActivities');
        Route::get('/{id}', [BookingController::class, 'show'])->name('api.bookings.show');
        Route::post('/', [BookingController::class, 'store'])->name('api.bookings.store');
        Route::put('/{id}', [BookingController::class, 'update'])->name('api.bookings.update');
        Route::put('/{id}/extend-time', [BookingController::class, 'extendTime'])->name('api.bookings.extendTime');
        Route::delete('/{id}', [BookingController::class, 'destroy'])->name('api.bookings.destroy');
        Route::post('/change-password', [BookingController::class, 'changePassword'])
            ->name('api.bookings.changePassword');
    });

    Route::prefix('search')->group(function () {
        Route::post('/lockers', [LockerController::class, 'search'])->name('api.locker.search');
    });

    Route::prefix('histories')->group(function () {
        Route::get('/booking', [BookingController::class, 'getHistoriesBooking'])->name('api.histories.get');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'get'])->name('api.notifications.get');
        Route::put('/{id}/status', [NotificationController::class, 'updateStatus'])
            ->name('api.notifications.update.status');
    });
});
