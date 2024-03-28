<?php

namespace App\Repositories;

use App\Models\Reservation;

use Carbon\CarbonImmutable;
use Illuminate\Support\LazyCollection;

interface ReservationRepositoryInterface
{
    public function sumReservationsOnDate(CarbonImmutable $dateTime, array $roomIds = []): int;

    /**
     * @param CarbonImmutable $startsAt
     * @param CarbonImmutable $endsAt
     * @param array $roomIds
     * @param array $ignoreBookingsIds
     * @return LazyCollection<Reservation>
     */
    public function getReservationsInPeriod(CarbonImmutable $startsAt,CarbonImmutable $endsAt, array $roomIds = [],array $ignoreBookingsIds =[]): LazyCollection;

}
