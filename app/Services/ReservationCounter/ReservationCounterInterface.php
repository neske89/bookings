<?php

namespace App\Services\ReservationCounter;

use Carbon\CarbonImmutable;

interface ReservationCounterInterface
{
    public function countBookings(CarbonImmutable $referenceDateTime, array $roomIds = []):int;
    public function countBlocks(CarbonImmutable $referenceDateTime, array $roomIds = []):int;
}
