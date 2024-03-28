<?php

namespace App\Repositories;
use App\Models\Booking;
use Carbon\CarbonImmutable;

interface BookingRepositoryInterface extends ReservationRepositoryInterface
{
    public function create(
        int $roomId,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt
    ): Booking;

    public function save(Booking $booking): Booking;

    public function getByIdOrFail(int $id): Booking;

}
