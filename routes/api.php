<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\OccupancyRateController;
use Illuminate\Support\Facades\Route;

Route::get('/daily-occupancy-rates/{date}', [OccupancyRateController::class, 'getDailyOccupancyRates'])
    ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->name('daily-occupancy-rates');

Route::get('/monthly-occupancy-rates/{date}', [OccupancyRateController::class, 'getMonthlyOccupancyRates'])
    ->where('Y-m', '[0-9]{4}-[0-9]{2}')
    ->name('monthly-occupancy-rates');

Route::post('/booking', [BookingController::class, 'store']);
Route::put('/booking/{id}', [BookingController::class, 'update']);
