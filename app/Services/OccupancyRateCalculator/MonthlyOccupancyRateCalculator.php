<?php

namespace App\Services\OccupancyRateCalculator;

use App\Services\ReservationCounter\TotalMonthlyReservationsCounter;
use App\Services\RoomCapacityRetriever;
use Carbon\Carbon;

class MonthlyOccupancyRateCalculator extends AbstractOccupancyRateCalculator
{
    public function __construct(
        private RoomCapacityRetriever $roomCapacityRetriever,
        private TotalMonthlyReservationsCounter $totalMonthlyReservationCounter,

    )
    {
    }
    public function calculate(Carbon $referenceDateTime, array $roomIds = []): float
    {
        $referenceDateTime = $referenceDateTime->copy()->startOfDay();
        $totalCapacity = $this->roomCapacityRetriever->getMonthlyCapacity($referenceDateTime,$roomIds);
        $totalBookings = $this->totalMonthlyReservationCounter->countBookings($referenceDateTime, $roomIds);
        $totalBlocks = $this->totalMonthlyReservationCounter->countBlocks($referenceDateTime, $roomIds);
        return $this->calculateOccupancy($totalCapacity, $totalBookings, $totalBlocks);
    }
}
