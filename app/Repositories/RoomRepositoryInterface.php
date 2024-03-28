<?php

namespace App\Repository;

use App\Models\Room;

interface RoomRepositoryInterface
{
    public function getTotalCapacity(array $roomIds=[]): int;
}
