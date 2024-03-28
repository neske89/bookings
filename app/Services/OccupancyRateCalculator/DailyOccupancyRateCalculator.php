<?php
namespace App\Services\OccupancyCalculator;

use App\Services\RoomCapacityRetriever;
use App\Services\ReservationCounter\TotalDailyReservationsCounter;
use Carbon\Carbon;

class DailyOccupancyRateCalculator extends AbstractOccupancyRateCalculator
{
    public function __construct(
        private RoomCapacityRetriever $roomCapacityRetriever,
        private TotalDailyReservationsCounter $totalDailyReservationsCounter
    ) {
    }

    public function calculate(Carbon $referenceDateTime, array $roomIds = []): float
    {
        $referenceDateTime->copy()->startOfDay();
        $totalCapacity = $this->roomCapacityRetriever->getDailyCapacity($roomIds);
        $totalBookings = $this->totalDailyReservationsCounter->countBookings($referenceDateTime, $roomIds);
        $totalBlocks = $this->totalDailyReservationsCounter->countBlocks($referenceDateTime, $roomIds);
        return $this->calculateOccupancy($totalCapacity, $totalBookings, $totalBlocks);
    }
}
