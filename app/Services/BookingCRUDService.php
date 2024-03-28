<?php

namespace App\Services;

use App\Http\DTO\BookingDTO;
use App\Exception\RoomIsAlreadyFullyBookedException;
use App\Models\Booking;
use App\Repositories\BookingRepositoryInterface;


class BookingCRUDService
{
    public function __construct(
        private RoomAvailabilityChecker $roomAvailabilityChecker,
        private BookingRepositoryInterface $bookingRepository,
    ) {
    }

    /**
     * @throws RoomIsAlreadyFullyBookedException
     */
    public function create(BookingDTO $bookingDTO): Booking
    {
        $this->roomAvailabilityChecker->check($bookingDTO->roomId, $bookingDTO->startsAt, $bookingDTO->endsAt);

        return $this->bookingRepository->create($bookingDTO->roomId, $bookingDTO->startsAt->startOfDay(), $bookingDTO->endsAt->endOfDay());
    }

    /**
     * @throws RoomIsAlreadyFullyBookedException
     */
    public function update(Booking $booking, BookingDTO $bookingDTO): Booking
    {
        $this->roomAvailabilityChecker->check(
            $bookingDTO->roomId,
            $bookingDTO->startsAt,
            $bookingDTO->endsAt,
            [$booking->id]
        );
        $booking->setRoomId($bookingDTO->roomId);
        $booking->setStartsAt($bookingDTO->startsAt->startOfDay());
        $booking->setEndsAt($bookingDTO->endsAt->endOfDay());
        return $this->bookingRepository->save($booking);

    }
}
