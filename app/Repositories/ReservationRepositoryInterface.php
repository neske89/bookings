<?php

namespace App\Repositories;

use Carbon\Carbon;

interface ReservationRepositoryInterface
{
    public function sumReservationsOnDate(Carbon $dateTime, array $roomIds = []): int;

}
