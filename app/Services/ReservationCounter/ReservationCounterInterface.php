<?php

namespace App\Services\ReservationCounter;

use Carbon\Carbon;

interface ReservationCounterInterface
{
    public function countBookings(Carbon $referenceDateTime, array $roomIds = []):int;
    public function countBlocks(Carbon $referenceDateTime, array $roomIds = []):int;
}
