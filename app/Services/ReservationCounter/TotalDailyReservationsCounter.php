<?php

namespace App\Services\ReservationCounter;

use App\Repository\BlockRepositoryInterface;
use App\Repository\BookingRepositoryInterface;
use Carbon\Carbon;

class TotalDailyReservationsCounter implements ReservationCounterInterface
{
    public function __construct(
        private BlockRepositoryInterface $blockRepository,
        private BookingRepositoryInterface $bookingRepository,
    ) {
    }

    public function countBookings(Carbon $referenceDateTime, array $roomIds = []): int
    {
        //get from repository
    }

    public function countBlocks(Carbon $referenceDateTime, array $roomIds = []): int
    {
        //get from repository
    }
}
