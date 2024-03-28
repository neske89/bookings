<?php
namespace App\Services\OccupancyRateCalculator;

use App\Services\RoomCapacityRetriever;
use App\Services\ReservationCounter\TotalDailyReservationsCounter;
use Carbon\CarbonImmutable;

class DailyOccupancyRateCalculator extends AbstractOccupancyRateCalculator
{
    public function __construct(
        private RoomCapacityRetriever $roomCapacityRetriever,
        private TotalDailyReservationsCounter $totalDailyReservationsCounter
    ) {
    }

    public function calculate(CarbonImmutable $referenceDateTime, array $roomIds = []): float
    {
        $totalCapacity = $this->roomCapacityRetriever->getDailyCapacity($roomIds);
        $totalBookings = $this->totalDailyReservationsCounter->countBookings($referenceDateTime->startOfDay(), $roomIds);
        $totalBlocks = $this->totalDailyReservationsCounter->countBlocks($referenceDateTime->startOfDay(), $roomIds);
        return $this->calculateOccupancy($totalCapacity, $totalBookings, $totalBlocks);
    }
}
