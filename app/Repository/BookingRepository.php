<?php

namespace App\Repository;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;

class BookingRepository extends ReservationRepository implements BookingRepositoryInterface
{
    protected function getQueryBuilder(): Builder {
        return Booking::query();
    }
}