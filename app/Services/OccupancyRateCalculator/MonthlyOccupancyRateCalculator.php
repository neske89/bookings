<?php

namespace App\Services\OccupancyRateCalculator;

use App\Services\ReservationCounter\TotalMonthlyReservationsCounter;
use App\Services\RoomCapacityRetriever;

use Carbon\CarbonImmutable;

class MonthlyOccupancyRateCalculator extends AbstractOccupancyRateCalculator
{
    public function __construct(
        private RoomCapacityRetriever $roomCapacityRetriever,
        private TotalMonthlyReservationsCounter $totalMonthlyReservationCounter,

    )
    {
    }
    public function calculate(CarbonImmutable $referenceDateTime, array $roomIds = []): float
    {
        $totalCapacity = $this->roomCapacityRetriever->getMonthlyCapacity($referenceDateTime->startOfDay(),$roomIds);
        $totalBookings = $this->totalMonthlyReservationCounter->sumBookings($referenceDateTime->startOfDay(), $roomIds);
        $totalBlocks = $this->totalMonthlyReservationCounter->countBlocks($referenceDateTime->startOfDay(), $roomIds);
        return $this->calculateOccupancy($totalCapacity, $totalBookings, $totalBlocks);
    }
}
