<?php

use App\Http\Controllers\Api\Bookings\BookingController;
use App\Http\Controllers\Api\Lockers\LockerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Locations\LocationsController;

Route::prefix('api-portal')->group(function () {
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationsController::class, 'get'])->name('portal.location.get');
    });

    Route::prefix('lockers')->group(function () {
        Route::get('get-available', [LockerController::class, 'search'])->name('portal.locker.available');
        Route::get('{lockerId}/slots', [LockerController::class, 'getModules'])->name('portal.locker.slots');
    });

    Route::prefix('bookings')->group(function () {
        Route::get('activities', [BookingController::class, 'getBookingActivities'])
            ->name('portal.booking.activities');
    });

    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store'])->name('portal.booking.store');
        Route::put('{bookingId}', [BookingController::class, 'update'])->name('portal.booking.update');
        Route::delete('{bookingId}', [BookingController::class, 'destroy'])->name('portal.booking.destroy');
    });
});
