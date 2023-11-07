<?php

use App\Http\Controllers\Api\Bookings\BookingController;
use App\Http\Controllers\Api\Lockers\LockerController;
use App\Http\Controllers\Api\Payments\PaymentController;
use App\Http\Controllers\Api\Payments\PaymentMethodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Locations\LocationsController;
use App\Http\Controllers\Api\Notifications\NotificationController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\HelpCall\HelpCallController;
use App\Http\Controllers\Api\HelpCall\HelpCallStdProblemController;

Route::prefix('api-portal')->group(function () {
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationsController::class, 'get'])->name('portal.location.get');
    });

    Route::prefix('lockers')->group(function () {
        Route::get('get-available', [LockerController::class, 'search'])->name('portal.locker.available');
        Route::get('get-activities', [LockerController::class, 'getLockerActivities'])
            ->name('portal.locker.activities');
        Route::get('{lockerId}/slots', [LockerController::class, 'getModules'])->name('portal.locker.slots');
        Route::get('{lockerId}/slots-short', [LockerController::class, 'getSlots'])->name('portal.locker.slots.short');

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
        Route::get('slot/{slotId}', [BookingController::class, 'getBookingsBySlotId'])
            ->name('api.bookings.getBookingsBySlotId');
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
            Route::post('/deposit', [PaymentController::class, 'deposit'])->name('portal.wallet.deposit');
            Route::get('{methodId}/deposit/callback', [PaymentController::class, 'depositCallback'])
                ->name('portal.wallet.deposit.callback');
            Route::post('/auth', [PaymentController::class, 'auth'])->name('portal.wallet.auth');
        });
        Route::prefix('methods')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('portal.payment.method.index');
            Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('portal.payment.method.show');
        });
        Route::prefix('transactions')->group(function () {
            Route::get('/', [PaymentController::class, 'getTransactions'])->name('portal.payment.transaction.get');
        });
    });

    Route::prefix('user')->group(function () {
        Route::put('/', [UserController::class, 'update'])->name('portal.user.update');
        Route::get('/list-admins', [UserController::class, 'getListAdmin'])->name('portal.user.list-admin');
        Route::get('user/create-token-register', [UserController::class, 'createTokenRegister'])
            ->name('portal.user.token-register.create');
        Route::get('user/token-register', [UserController::class, 'getTokenRegister'])
            ->name('portal.user.token-register');
        Route::get('user/token-register/delete/{id}', [UserController::class, 'deleteTokenRegister'])
            ->name('portal.user.token-register.delete');
    });

    Route::prefix('help-call')->group(function () {
        Route::prefix('std-problems')->group(function () {
            Route::get('/', [HelpCallStdProblemController::class, 'index'])->name('portal.help-call.std-problems');
            Route::put('/{id}', [HelpCallStdProblemController::class, 'update'])
                ->name('portal.help-call.std-problems.update');
            Route::post('/', [HelpCallStdProblemController::class, 'store'])
                ->name('portal.help-call.std-problems.store');
            Route::delete('/{id}', [HelpCallStdProblemController::class, 'destroy'])
                ->name('portal.help-call.std-problems.destroy');
        });
        Route::prefix('/')->group(function () {
            Route::get('/user', [HelpCallController::class, 'getHelpCallUser'])->name('portal.help-call.user');
            Route::get('/admin', [HelpCallController::class, 'getHelpCallAdmin'])->name('portal.help-call.admin');
            Route::post('/', [HelpCallController::class, 'store'])->name('portal.help-call.store');
            Route::get('/{id}', [HelpCallController::class, 'show'])->name('portal.help-call.show');
            Route::post('/{id}/comment', [HelpCallController::class, 'comment'])
                ->name('portal.help-call.comment');
            Route::put('/{id}', [HelpCallController::class, 'update'])
                ->name('portal.help-call.update');
        });
    });
});
