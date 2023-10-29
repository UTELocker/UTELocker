<?php

use App\Http\Controllers\Api\Bookings\BookingController;
use App\Http\Controllers\Api\Lockers\LockerController;
use App\Http\Controllers\Api\Payments\PaymentController;
use App\Http\Controllers\Api\Payments\PaymentMethodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Locations\LocationsController;
use App\Http\Controllers\Api\Notifications\NotificationController;

Route::prefix('api-portal')->group(function () {
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationsController::class, 'get'])->name('portal.location.get');
    });

    Route::prefix('lockers')->group(function () {
        Route::get('get-available', [LockerController::class, 'search'])->name('portal.locker.available');
        Route::get('{lockerId}/slots', [LockerController::class, 'getModules'])->name('portal.locker.slots');

    });

    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store'])->name('portal.booking.store');
        Route::put('{bookingId}', [BookingController::class, 'update'])->name('portal.booking.update');
        Route::delete('{bookingId}', [BookingController::class, 'destroy'])->name('portal.booking.destroy');
        Route::get('activities', [BookingController::class, 'getBookingActivities'])
            ->name('portal.booking.activities');
        Route::put('/{id}/extend-time', [BookingController::class, 'extendTime'])
            ->name('portal.bookings.extendTime');
        Route::post('/change-password', [BookingController::class, 'changePassword'])
            ->name('portal.bookings.changePassword');
    });

    Route::prefix('histories')->group(function () {
        Route::get('/booking', [BookingController::class, 'getHistoriesBooking'])->name('portal.histories.get');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'get'])->name('portal.notifications.get');
        Route::put('/{id}/status', [NotificationController::class, 'updateStatus'])
            ->name('portal.notifications.update.status');
        Route::post('/', [NotificationController::class, 'store'])->name('portal.notifications.store');
    });

    Route::prefix('payments')->group(function () {
        Route::prefix('wallets')->group(function () {
            Route::get('/getWallet', [PaymentController::class, 'getWallet'])->name('portal.wallet.get');
            Route::get('/deposit', [PaymentController::class, 'deposit'])->name('portal.wallet.deposit');
            Route::get('/deposit/callback', [PaymentController::class, 'depositCallback'])
                ->name('portal.wallet.deposit.callback');
        });
        Route::prefix('methods')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('portal.payment.method.index');
            Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('portal.payment.method.show');
        });
    });
});
