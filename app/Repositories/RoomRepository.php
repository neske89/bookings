<?php

namespace App\Repository;


use App\Models\Room;

class RoomRepository implements RoomRepositoryInterface
{
    public function getTotalCapacity(array $roomIds=[]): int
    {
        $query = Room::query();
        if ($roomIds) {
            $query->whereIn('id', $roomIds);
        }
        return $query->sum('capacity');
    }
}
