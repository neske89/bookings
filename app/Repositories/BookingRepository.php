<?php

namespace App\Repositories;

use App\Models\Booking;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class BookingRepository extends ReservationRepository implements BookingRepositoryInterface
{
    protected function getQueryBuilder(): Builder {
        return Booking::query();
    }
    public function create(int $roomId, CarbonImmutable $startsAt, CarbonImmutable $endsAt): Booking
    {

        $booking = new Booking();
        $booking->setRoomId($roomId);
        $booking->setStartsAt($startsAt);
        $booking->setEndsAt($endsAt);
        $this->save($booking);
        return $booking;
    }

    public function save(Booking $booking): Booking
    {
        $saved =$booking->save();
        if (!$saved) {
            throw new \RuntimeException("Booking could not be saved");
        }
        return $booking;
    }

    public function getByIdOrFail(int $id): Booking
    {
        return Booking::findOrFail($id);
    }
}
