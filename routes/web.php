<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class,'index'])
            ->name('dashboard');
    });
