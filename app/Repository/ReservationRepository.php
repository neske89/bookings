<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

abstract class ReservationRepository implements ReservationRepositoryInterface
{
    abstract protected function getQueryBuilder(): Builder;


}
