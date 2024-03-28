<?php

namespace App\Service\OccupancyCalculator;

abstract class AbstractOccupancyRateCalculator implements OccupancyRateCalculatorInterface
{
    final protected function calculateOccupancy(int $totalCapacity, int $totalBookings, int $totalBlocks): float
    {
        if ($totalCapacity === 0) {
            return 0;
        }
        if ($totalCapacity - $totalBlocks < 0) {
            throw new \LogicException('Total capacity cannot be less than total blocks');
        }
        if ($totalCapacity - $totalBlocks === 0) {
            //total bookings should be 0 in this case
            return 0;
        }

        return round($totalBookings / ($totalCapacity - $totalBlocks), 2);
    }
}
