<?php


use Carbon\Carbon;

interface OccupancyRateCalculatorInterface
{
    public function calculate(Carbon $dateTime, array $roomIds= []):float;
}
