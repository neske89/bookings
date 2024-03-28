<?php

namespace Tests\Integration\Repository;

use App\Repositories\BookingRepositoryInterface;
use Carbon\Carbon;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BookingRepositoryInterface $bookingRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookingRepository = app(BookingRepositoryInterface::class);

        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    public function testGetReservationsInMonth(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $bookings = $this->bookingRepository->getReservationsInMonth(Carbon::parse($referenceDate), $roomIds);
        $this->assertEquals($expectedResult, $bookings->count());
    }

    /**
     * @dataProvider sumReservationsOnDateDataProvider
     */
    public function testSumReservationsOnDate(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $result = $this->bookingRepository->sumReservationsOnDate(Carbon::parse($referenceDate), $roomIds);
        $this->assertEquals($expectedResult, $result);
    }

    public static function sumReservationsOnDateDataProvider(): array
    {
        return [
            '2nd January' => ['2024-01-02 00:00:00', [], 3],
            '2nd January Room A' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID], 2],
            '2nd January Room A & not existing id' => ['2024-01-02 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID, 10], 2],
            'On date spanning trough whole month' => ['2027-09-02 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 2],
            'On date starting before and ending after month' => ['2027-11-05 00:00:00', [], 3],
            'On date starting before and ending after month single room' => [
                '2027-11-08 00:00:00',
                [RoomSeeder::ROOM_2_CAPACITY_ID],
                2,
            ],
            '8th August Room C' => ['2024-08-08 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 0],
        ];
    }


}
