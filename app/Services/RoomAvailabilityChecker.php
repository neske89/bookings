<?php

namespace App\Services;

use App\Exception\RoomIsAlreadyFullyBookedException;
use App\Repositories\BlockRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\RoomRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class RoomAvailabilityChecker
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private BlockRepositoryInterface $blockRepository,
        private RoomRepositoryInterface $roomRepository
    ) {
    }
    public function check(int $roomId,CarbonImmutable $startsAt,CarbonImmutable $endsAt,array $ignoreBookingIds=[] ):void {
        $room = $this->roomRepository->findByIdOrFail($roomId);
        $bookings = $this->bookingRepository->getReservationsInPeriod($startsAt, $endsAt,[$roomId],$ignoreBookingIds);
        $blocks =  $this->blockRepository->getReservationsInPeriod($startsAt, $endsAt,[$roomId],$ignoreBookingIds);

        $datePointer = new Carbon($startsAt->startOfDay());
        while ($datePointer <= $endsAt) {
            $dailyCapacity = $room->getCapacity();
            foreach ($bookings as $booking) {
                if ($datePointer->between($booking->getStartsAt(), $booking->getEndsAt())) {
                    --$dailyCapacity;
                }
                if ($dailyCapacity === 0) {
                    throw new RoomIsAlreadyFullyBookedException("Room is already fully booked for date {$datePointer->toDateString()}");
                }
            }
            foreach ($blocks as $block) {
                if ($datePointer->between($block->getStartsAt(), $block->getEndsAt())) {
                    --$dailyCapacity;
                }
                if ($dailyCapacity === 0) {
                    throw new RoomIsAlreadyFullyBookedException("Room is already fully booked for date {$datePointer->toDateString()}");
                }
            }
            $datePointer->addDay();
        }
    }
}
