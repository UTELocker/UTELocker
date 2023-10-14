<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Locations\LocationsController;
use App\Http\Controllers\Api\Lockers\LockersController;
use App\Http\Controllers\Api\Bookings\BookingsController;
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
        Route::get('/', [LockersController::class, 'get'])->name('api.locker.get');
        Route::post('/{id}/modules', [LockersController::class, 'getModules'])->name('api.locker.getModules');
    });

    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingsController::class, 'getOfUser'])->name('api.bookings.getOfUser');
        Route::get('/{id}', [BookingsController::class, 'show'])->name('api.bookings.show');
        Route::post('/', [BookingsController::class, 'store'])->name('api.bookings.store');
        Route::put('/{id}', [BookingsController::class, 'update'])->name('api.bookings.update');
        Route::delete('/{id}', [BookingsController::class, 'destroy'])->name('api.bookings.destroy');
        Route::post('/change-password', [BookingsController::class, 'changePassword'])
            ->name('api.bookings.changePassword');
    });

    Route::prefix('search')->group(function () {
        Route::post('/lockers', [LockersController::class, 'search'])->name('api.locker.search');
    });
});
