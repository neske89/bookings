<?php

namespace App\Repositories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Builder;

class BlockRepository extends ReservationRepository implements BlockRepositoryInterface
{
    protected function getQueryBuilder(): Builder {
        return Block::query();
    }

}
