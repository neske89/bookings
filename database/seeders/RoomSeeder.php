<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public const int ROOM_6_CAPACITY_ID = 3;
    public const int ROOM_4_CAPACITY_ID = 4;
    public const int ROOM_2_CAPACITY_ID = 5;


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create(['id' => self::ROOM_6_CAPACITY_ID, 'capacity' => 6]);
        Room::create(['id' => self::ROOM_4_CAPACITY_ID, 'capacity' => 4]);
        Room::create(['id' => self::ROOM_2_CAPACITY_ID,  'capacity' => 2]);
    }
}
