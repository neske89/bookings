<?php
namespace App\Services\OccupancyRateCalculator;

use Carbon\CarbonImmutable;

interface OccupancyRateCalculatorInterface
{
    public function calculate(CarbonImmutable $referenceDateTime, array $roomIds= []):float;
}
