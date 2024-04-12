<?php

namespace App\Repositories;

use App\Models\Booking;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

abstract class ReservationRepository implements ReservationRepositoryInterface
{
    abstract protected function getQueryBuilder(): Builder;

    public function sumReservationsOnDate(CarbonImmutable $dateTime, array $roomIds = []): int
    {
        $query = $this->getQueryBuilder()
            ->where('starts_at', '<=', $dateTime)
            ->where('ends_at', '>=', $dateTime);
        if (!empty($roomIds)) {
            $query->whereIn('room_id', $roomIds);
        }

        return $query->count();
    }

    public function getReservationsInPeriod(CarbonImmutable $startsAt, CarbonImmutable $endsAt, array $roomIds = [],array $ignoreBookingsIds=[]): LazyCollection
    {
        $startOfPeriod = $startsAt->startOfDay()->toDateTimeString();
        $endOfPeriod = $endsAt->endOfDay()->toDateTimeString();


        $query = $this->getQueryBuilder();
        if (!empty($roomIds)) {
            $query->whereIn('room_id', $roomIds);
        }
        if (!empty($ignoreBookingsIds)) {
            $query->whereNotIn('id', $ignoreBookingsIds);
        }
        $query->where(function ($query) use ($startOfPeriod, $endOfPeriod) {
            $query->where(function ($query) use ($startOfPeriod, $endOfPeriod) {
                $query->whereBetween('starts_at', [$startOfPeriod, $endOfPeriod])
                    ->orWhereBetween('ends_at', [$startOfPeriod, $endOfPeriod]);
            })
                ->orWhere(function ($query) use ($startOfPeriod, $endOfPeriod) {
                    $query->where('starts_at', '<=', $startOfPeriod)
                        ->where('ends_at', '>=', $endOfPeriod);
                });
        });
        return LazyCollection::make(function () use ($query) {
            foreach ($query->cursor() as $booking) {
                yield $booking;
            }
        });
    }

    public function sumReservationsInPeriod(
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        array $roomIds = [],
        array $ignoreBookingsIds = []
    ): int {
        $formattedStartsAt = $startsAt->format('Y-m-d H:i:s');
        $formattedEndsAt = $endsAt->format('Y-m-d H:i:s');
        // Format input dates to SQL date format
        $formattedStartsAt = $startsAt->format('Y-m-d H:i:s');
        $formattedEndsAt = $endsAt->format('Y-m-d H:i:s');

        // SQL query to sum the days of reservations within the specified period
        $result = DB::SELECT("
        SELECT SUM(days_in_month) AS total_days_in_month
        FROM (
            SELECT
                DATEDIFF(
                    LEAST(?, ends_at),
                    GREATEST(?, starts_at)
                ) + 1 AS days_in_month
            FROM
                bookings
            WHERE
                (starts_at BETWEEN ? AND ? OR
                 ends_at BETWEEN ? AND ?) OR
                (starts_at <= ? AND ends_at >= ?)
            AND room_id IN (?)
            AND id NOT IN (?)
        ) AS subquery;", [
            $formattedEndsAt, $formattedStartsAt, // for LEAST and GREATEST
            $formattedStartsAt, $formattedEndsAt, // for BETWEEN conditions
            $formattedStartsAt, $formattedEndsAt, // for BETWEEN conditions
            $formattedStartsAt, $formattedEndsAt, // for <= and >= conditions
            implode(',',$roomIds),
            implode(',',$ignoreBookingsIds)
        ]);

        return $result[0]->total_days_in_month;
    }
}

