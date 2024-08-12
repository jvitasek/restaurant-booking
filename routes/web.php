<?php

declare(strict_types=1);

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::group(['prefix' => __('rezervace')], function () {
        Route::get('/', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('booking.destroy');
    });
});

require __DIR__.'/auth.php';
