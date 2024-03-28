<?php

namespace Tests\Integration\Repository;

use App\Repositories\BookingRepositoryInterface;

use Carbon\CarbonImmutable;
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

    /**
     * @dataProvider getReservationsInPeriodDataProvider
     */
    public function testGetReservationsInPeriod(
        string $startsAt,
        string $endsAt,
        array $roomIds,
        array $ignoredBookingIds,
        int $expectedResult
    ): void {
        $startsAtCarbon = CarbonImmutable::parse($startsAt);
        $endsAtCarbon = CarbonImmutable::parse($endsAt);
        $bookings = $this->bookingRepository->getReservationsInPeriod(
            $startsAtCarbon,
            $endsAtCarbon,
            $roomIds,
            $ignoredBookingIds
        );
        $this->assertEquals($expectedResult, $bookings->count());
    }

    public static function getReservationsInPeriodDataProvider(): array
    {
        //startsAt, endsAt, roomIds, ignoredBookingIds, expectedResult
        return [
            'multiple bookings with different periods' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [],
                [],
                4,
            ],
            'multiple bookings with different periods with ignored booking' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [],
                [1],
                3,
            ],
            'multiple bookings with different periods with ignored booking with specific room' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [RoomSeeder::ROOM_6_CAPACITY_ID, RoomSeeder::ROOM_2_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID],
                [1],
                3,
            ],

            'multiple bookings with different periods specific Rooms' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID],
                [],
                2,
            ],
            'period spans over multiple months' => [
                '2027-10-30 00:00:00',
                '2027-11-05 23:59:59',
                [],
                [],
                3,
            ],
            'period spans over multiple months specific room' => [
                '2027-10-30 00:00:00',
                '2027-11-05 23:59:59',
                [RoomSeeder::ROOM_6_CAPACITY_ID],
                [],
                1,
            ],
            'In Month' => [
                '2024-01-01 00:00:00',
                '2024-01-31 23:59:59',
                [],
                [],
                5,
            ],
            'In Month Single Room ' => [
                '2024-01-01 00:00:00',
                '2024-01-31 23:59:59',
                [RoomSeeder::ROOM_6_CAPACITY_ID],
                [],
                2,
            ],
            'In Month Spanning trough different months' => [
                '2024-01-01 00:00:00',
                '2024-01-31 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID],
                [],
                2,
            ],
            'In Month spanning trough whole month' => [
                '2027-09-01 00:00:00',
                '2027-09-30 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID],
                [],
                2,
            ],
            'In Month starting before and ending after month' => [
                '2027-11-01 00:00:00',
                '2027-11-01 23:59:59',
                [],
                [],
                3,
            ],
            'In Month starting before and ending after month single room' => [
                '2027-11-01 00:00:00',
                '2027-11-30 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID],
                [],
                2,
            ],
            'Month without reservations' => [
                '2024-08-01 00:00:00',
                '2024-08-01 23:59:59',
                [],
                [],
                0,
            ],
        ];
    }

    /**
     * @dataProvider sumReservationsOnDateDataProvider
     */
    public function testSumReservationsOnDate(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $result = $this->bookingRepository->sumReservationsOnDate(CarbonImmutable::parse($referenceDate), $roomIds);
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
