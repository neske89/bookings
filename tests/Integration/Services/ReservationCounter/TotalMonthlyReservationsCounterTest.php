<?php

namespace ReservationCounter;

use App\Services\ReservationCounter\TotalMonthlyReservationsCounter;

use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TotalMonthlyReservationsCounterTest extends TestCase
{
    use RefreshDatabase;

    private TotalMonthlyReservationsCounter $totalMonthlyReservationsCounter;

    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();

        $this->totalMonthlyReservationsCounter = app(TotalMonthlyReservationsCounter::class);

        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    /**
     * @dataProvider countBookingsDataProvider
     **/

    public function testCountBookings(string $forMonthString, array $roomIds, int $expectedBookings): void
    {
        $forMonth = CarbonImmutable::parse($forMonthString);

        $result = $this->totalMonthlyReservationsCounter->countBookings($forMonth, $roomIds);
        $this->assertEquals($expectedBookings, $result);
    }

    public static function countBookingsDataProvider(): array
    {
        return [
            'January 2024 ' => ['2024-01-01 00:00:00', [], 29],
            'January 2024 Specific Room' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID], 14],
            'January 2024 Specific Rooms' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID,RoomSeeder::ROOM_4_CAPACITY_ID], 22],
            'February 2024 ' => ['2024-02-01 00:00:00', [], 2],
            'October 2027 - bookings starts in october ends in december' =>['2027-10-01 00:00:00', [], 87],
            'November 2027 - bookings starts in october ends in december' =>['2027-11-01 00:00:00', [], 90],
            'December 2027 - bookings starts in october ends in december' =>['2027-12-01 00:00:00', [], 9],
        ];
    }

    /**
     * @dataProvider countBlocksDataProvider
     **/
    public function testCountBlocks(string $forMonthString, array $roomIds, int $expectedBookings): void
    {
        $forMonth = CarbonImmutable::parse($forMonthString);
        $result = $this->totalMonthlyReservationsCounter->countBlocks($forMonth, $roomIds);
        $this->assertEquals($expectedBookings, $result);
    }

    public static function countBlocksDataProvider(): array
    {
        return [
            'January all rooms' => ['2024-01-03 00:00:00', [], 30],
            'January Specific Room' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID], 20],
            'January Specific Room No Blocks' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID], 0],

        ];
    }
}
