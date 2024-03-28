<?php
namespace App\Services\OccupancyRateCalculator;


use Carbon\Carbon;

interface OccupancyRateCalculatorInterface
{
    public function calculate(Carbon $referenceDateTime, array $roomIds= []):float;
}
