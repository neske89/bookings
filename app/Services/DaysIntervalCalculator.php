<?php

namespace App\Services;



use Carbon\CarbonImmutable;

class DaysIntervalCalculator
{
    public function calculate(CarbonImmutable $startDateTime, CarbonImmutable $endDateTime): int
    {
        if ($startDateTime->gt($endDateTime)) {
            throw new \LogicException('Start date cannot be greater than end date');
        }
        $start = $startDateTime->copy()->startOfDay();
        $end = $endDateTime->copy()->endOfDay();
        return round($start->diffInDays($end,true));
    }
}
