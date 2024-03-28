<?php

namespace App\Repositories;

use App\Models\Room;

interface RoomRepositoryInterface
{
    public function getTotalCapacity(array $roomIds=[]): int;
}
