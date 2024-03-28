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
            //2027-01-01 00:00:00 3 blocks
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-01-01 00:00:00', 'ends_at' => '2027-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-01-01 00:00:00', 'ends_at' => '2027-01-10 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-01-01 00:00:00', 'ends_at' => '2027-01-10 23:59:59'],
            //february 2028
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2028-02-01 00:00:00', 'ends_at' => '2028-02-28 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2028-02-01 00:00:00', 'ends_at' => '2028-02-28 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2028-02-01 00:00:00', 'ends_at' => '2028-02-28 23:59:59'],
        ];
    }
    private function getBookingsData(): array
    {
        return [

            ['id'=> 1,'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2024-01-02 00:00:00', 'ends_at' => '2024-01-08 23:59:59'],
            ['id'=> 2,'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2024-01-02 00:00:00', 'ends_at' => '2024-01-08 23:59:59'],

            ['id'=> 3,'room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2024-01-10 00:00:00', 'ends_at' => '2024-01-17 23:59:59'],

            ['id'=> 4,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2023-12-28 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],
            ['id'=> 5,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2024-01-30 00:00:00', 'ends_at' => '2024-02-02 23:59:59'],
            //end january 2024

            ['id'=> 6,'room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2029-04-25 00:00:00', 'ends_at' => '2029-05-03 23:59:59'],
            ['id'=> 7,'room_id' => RoomSeeder::ROOM_4_CAPACITY_ID, 'starts_at' => '2029-04-25 00:00:00', 'ends_at' => '2029-05-03 23:59:59'],

            ['id'=> 8,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2029-04-24 00:00:00', 'ends_at' => '2029-05-03 23:59:59'],

            //2 bookings in november 2026
            ['id'=>9,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-12-03 00:00:00', 'ends_at' => '2026-12-06 23:59:59'],
            ['id'=>10,'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-12-03 00:00:00', 'ends_at' => '2026-12-06 23:59:59'],

            //2 bookings in december 2026
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-12-03 00:00:00', 'ends_at' => '2026-12-03 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2026-12-03 00:00:00', 'ends_at' => '2026-12-03 23:59:59'],
            //3 bookings in october or november or december 2027
            ['room_id' => RoomSeeder::ROOM_6_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-10-03 00:00:00', 'ends_at' => '2027-12-03 23:59:59'],
            //2 bookings for whole september 2027
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-09-01 00:00:00', 'ends_at' => '2027-09-30 23:59:59'],
            ['room_id' => RoomSeeder::ROOM_2_CAPACITY_ID, 'starts_at' => '2027-09-01 00:00:00', 'ends_at' => '2027-09-30 23:59:59'],

        ];
    }
}
