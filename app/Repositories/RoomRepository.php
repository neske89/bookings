<?php

namespace App\Repositories;


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
    public function findByIdOrFail(int $id): Room
    {
        return Room::findOrFail($id);
    }
}
