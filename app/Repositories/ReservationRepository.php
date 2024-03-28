<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

abstract class ReservationRepository implements ReservationRepositoryInterface
{
    abstract protected function getQueryBuilder(): Builder;

    public function sumReservationsOnDate(Carbon $dateTime, array $roomIds = []): int
    {
        $query = $this->getQueryBuilder()
            ->where('starts_at', '<=', $dateTime)
            ->where('ends_at', '>=', $dateTime);
        if (!empty($roomIds)) {
            $query->whereIn('room_id', $roomIds);
        }

        return $query->count();
    }

}

