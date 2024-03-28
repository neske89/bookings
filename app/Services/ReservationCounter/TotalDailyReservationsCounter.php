<?php

namespace App\Services\ReservationCounter;

use App\Repositories\BlockRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
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
        return $this->bookingRepository->sumReservationsOnDate($referenceDateTime, $roomIds);
    }

    public function countBlocks(Carbon $referenceDateTime, array $roomIds = []): int
    {
        return $this->blockRepository->sumReservationsOnDate($referenceDateTime, $roomIds);
    }
}
