<?php

namespace App\Http\Controllers;

use App\Http\DTO\OccupancyRateDTO;
use App\Http\Requests\DailyOccupancyRatesRequest;
use App\Http\Requests\MonthlyOccupancyRatesRequest;
use App\Services\OccupancyRateCalculator\DailyOccupancyRateCalculator;
use App\Services\OccupancyRateCalculator\MonthlyOccupancyRateCalculator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class OccupancyRateController extends Controller
{
    public function getDailyOccupancyRates(
        DailyOccupancyRatesRequest $request,
        DailyOccupancyRateCalculator $dailyOccupancyRateCalculator
    ): JsonResponse {
        $validated = $request->validated();
        $date = Carbon::createFromFormat('Y-m-d', $validated['date']);
        $roomIds = $validated['room_ids'] ?? [];
        $occupancyRate = $dailyOccupancyRateCalculator->calculate($date, $roomIds);

        return new JsonResponse(new OccupancyRateDTO($occupancyRate));
    }

    public function getMonthlyOccupancyRates(
        MonthlyOccupancyRatesRequest $request,
        MonthlyOccupancyRateCalculator $monthlyOccupancyRateCalculator
    ): JsonResponse {
        $validated = $request->validated();
        $date = Carbon::createFromFormat('Y-m', $validated['date']);
        $roomIds = $validated['room_ids'] ?? [];

        $occupancyRate = $monthlyOccupancyRateCalculator->calculate($date, $roomIds);

        return new JsonResponse(new OccupancyRateDTO($occupancyRate));
    }
}
