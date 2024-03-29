<?php

use App\Http\Controllers\Admin\Settings\SiteGroupSettingController;
use App\Http\Controllers\LockerSystemController;

Route::prefix('/')->middleware('auth.license')->group(function () {
    Route::get('/set-up/config', [LockerSystemController::class, 'settingsLockerSystem'])
        ->name('api_qt.lockers.slots.set_up_config');
    Route::get('/log-active', [LockerSystemController::class, 'logActive'])
        ->name('api_qt.lockers.slots.log_active');
    Route::post('/password', [LockerSystemController::class, 'systemPassword'])
        ->name('api_qt.lockers.slots.password');
    Route::get('/sync', [LockerSystemController::class, 'syn'])
        ->name('api_qt.lockers.slots.syn');
    Route::post('/reset-pass', [LockerSystemController::class, 'resetPass'])
        ->name('api_qt.lockers.slots.resetPass');
    Route::post('/forgot-close', [LockerSystemController::class, 'forgotClose'])
        ->name('api_qt.lockers.slots.forgotClose');

    //test pusher for VĨ
    Route::get('/pusher', [LockerSystemController::class, 'pusher'])
        ->name('api_qt.lockers.slots.pusher');
});
