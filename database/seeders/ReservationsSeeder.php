<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class ReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedBookings();
        $this->seedBlocks();
    }

    private function seedBookings(): void
    {
        $bookings = $this->getBookingsData();
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }

    private function seedBlocks(): void
    {
        $blocks = $this->getBlocksData();
        foreach ($blocks as $block) {
            Block::create($block);
        }
    }

    private function getBlocksData(): array
    {
        return [
            ['room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-01-01 00:00:00', 'ends_at' => '2026-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-01-01 00:00:00', 'ends_at' => '2026-01-10 23:59:59'],

        ];
    }
    private function getBookingsData(): array
    {
        return [

            ['id'=> 1,'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2024-01-02 00:00:00', 'ends_at' => '2024-01-08 23:59:59'],
            ['id'=> 2,'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2024-01-02 00:00:00', 'ends_at' => '2024-01-08 23:59:59'],

            ['id'=> 3,'room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2024-01-10 00:00:00', 'ends_at' => '2024-01-17 23:59:59'],

            ['id'=> 4,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2023-12-28 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],

            ['room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            //2 bookings for whole september 2027
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-09-01 00:00:00', 'ends_at' => '2027-09-30 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-09-01 00:00:00', 'ends_at' => '2027-09-30 23:59:59'],

        ];
    }
}
