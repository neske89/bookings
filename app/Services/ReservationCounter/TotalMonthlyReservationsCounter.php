<?php

namespace App\Services\ReservationCounter;

use App\Repositories\BlockRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Services\DaysIntervalCalculator;
use Carbon\CarbonImmutable;


class TotalMonthlyReservationsCounter implements ReservationCounterInterface
{
    public function __construct(
        private BlockRepositoryInterface $blockRepository,
        private BookingRepositoryInterface $bookingRepository,
        private DaysIntervalCalculator $daysIntervalCalculator
    ) {
    }

    public function countBookings(CarbonImmutable $referenceDateTime, array $roomIds = []): int
    {
        $startOfMonth = $referenceDateTime->copy()->startOfMonth();
        $endOfMonth = $referenceDateTime->copy()->endOfMonth();
        $total = 0;
        foreach ($this->bookingRepository->getReservationsInPeriod($startOfMonth, $endOfMonth, $roomIds) as $booking) {
            $total += $this->countBookingDurationInDays(
                $referenceDateTime,
                $booking->getStartsAt(),
                $booking->getEndsAt()
            );
        }

        return $total;
    }

    public function countBlocks(CarbonImmutable $referenceDateTime, array $roomIds = []): int
    {
        $startOfMonth = $referenceDateTime->copy()->startOfMonth();
        $endOfMonth = $referenceDateTime->copy()->endOfMonth();
        $total = 0;
        foreach ($this->blockRepository->getReservationsInPeriod($startOfMonth, $endOfMonth, $roomIds) as $block) {
            $total += $this->countBookingDurationInDays($referenceDateTime, $block->getStartsAt(), $block->getEndsAt());
        }

        return $total;
    }

    /**
     * Calculates the number of days a specific booking reservation spans within a given month.
     */
    private function countBookingDurationInDays(CarbonImmutable $forMonth, CarbonImmutable $startDate, CarbonImmutable $endDate): int
    {
        // Check if startDate is before the beginning of forMonth
        if ($startDate->lessThan($forMonth->startOfMonth())) {
            $startDate = $forMonth->startOfMonth();
        }

        // Check if endDate is after the end of forMonth
        if ($endDate->greaterThan($forMonth->copy()->endOfMonth())) {
            $endDate = $forMonth->endOfMonth();
        }

        return $this->daysIntervalCalculator->calculate($startDate, $endDate);
    }
}
