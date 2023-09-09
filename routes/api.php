<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;

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
        Route::get('/', [UserController::class, 'get'])->name('api.location.get');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.location.show');
    });
    Route::prefix('lockers')->group(function () {
        Route::get('/', [UserController::class, 'get'])->name('api.locker.get');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.locker.show');
    });
});
