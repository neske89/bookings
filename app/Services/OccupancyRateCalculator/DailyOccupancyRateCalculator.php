<?php
namespace App\Services\OccupancyCalculator;

use App\Service\RoomCapacityRetriever;
use Carbon\Carbon;

class DailyOccupancyRateCalculator extends AbstractOccupancyRateCalculator
{
    //generate constructor
    public function __construct(
        private RoomCapacityRetriever $roomCapacityRetriever,
    ) {
    }

    public function calculate(Carbon $referenceDateTime, array $roomIds = []): float
    {
        $referenceDateTime->copy()->startOfDay();
        $totalCapacity = $this->roomCapacityRetriever->getDailyCapacity($roomIds);
        $totalBookings = 0;
        $totalBlocks = 0;
        //$sum total bookings
        //sum total blocks
        return $this->calculateOccupancy($totalCapacity, $totalBookings, $totalBlocks);
    }
}
