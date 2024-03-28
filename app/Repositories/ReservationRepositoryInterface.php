<?php

namespace App\Repositories;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;

interface ReservationRepositoryInterface
{
    public function sumReservationsOnDate(Carbon $dateTime, array $roomIds = []): int;

    /**
     * @param Carbon $forMonth
     * @param array $roomIds
     * @return LazyCollection<Reservation>
     */
    public function getReservationsInMonth(Carbon $forMonth, array $roomIds = []): LazyCollection;

}
