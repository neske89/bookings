<?php

namespace ReservationCounter;

use App\Services\ReservationCounter\TotalDailyReservationsCounter;

use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TotalDailyReservationsCounterTest extends TestCase
{
    use RefreshDatabase;
    private TotalDailyReservationsCounter $totalDailyReservationsCounter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->totalDailyReservationsCounter = app(TotalDailyReservationsCounter::class);

        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    /**
     * @dataProvider countBookingsDataProvider
     **/

    public function testCountBookings(string $forMonthString, array $roomIds, int $expectedBookings): void
    {
        $forMonth = CarbonImmutable::parse($forMonthString);
        $roomIds = [];


        $result = $this->totalDailyReservationsCounter->countBookings($forMonth, $roomIds);
        $this->assertEquals($expectedBookings, $result);
    }

    public static function countBookingsDataProvider(): array
    {
        return [
            'All Rooms 2024-01-02 00:00:00' => ['2024-01-02 00:00:00', [], 3],
            'Single Room 2024-01-02 00:00:00' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID], 3],
            'Multiple Rooms 2024-01-02 00:00:00' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID,RoomSeeder::ROOM_4_CAPACITY_ID], 3],
            'Room no bookings 2024-01-02 00:00:00' => ['2024-08-02 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 0],
            '8th August Room C' => ['2024-08-08 00:00:00', [], 0]
        ];
    }

    /**
     * @dataProvider countBlocksDataProvider
     **/
    public function testCountBlocks(string $forMonthString, array $roomIds, int $expectedBookings): void
    {
        $forMonth = CarbonImmutable::parse($forMonthString);

        $result = $this->totalDailyReservationsCounter->countBlocks($forMonth, $roomIds);
        $this->assertEquals($expectedBookings, $result);
    }

    public static function countBlocksDataProvider(): array
    {
        return [
            '2024-01-02 00:00:00 all rooms' => ['2024-01-02 00:00:00', [], 3],
            '2024-01-02 00:00:00 Single Room' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID], 2],
            '2024-01-02 00:00:00 No Blocks' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID], 0],
            '2024-01-02 00:00:00 Multiple Rooms' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID], 3],

        ];
    }
}
