<?php
namespace App\Service\OccupancyCalculator;


use Carbon\Carbon;

interface OccupancyRateCalculatorInterface
{
    public function calculate(Carbon $referenceDateTime, array $roomIds= []):float;
}
