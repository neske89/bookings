<?php

namespace App\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
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
}

