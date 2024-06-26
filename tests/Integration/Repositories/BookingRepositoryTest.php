<?php

namespace Tests\Integration\Repository;

use App\Models\Booking;
use App\Repositories\BookingRepositoryInterface;

use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

    public function testSaveThrowsException():void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Booking could not be saved");
        $booking = new Booking();
        $booking->id = 1;
        $booking->room_id = RoomSeeder::ROOM_6_CAPACITY_ID;
        Event::listen('eloquent.saving: ' . Booking::class, function ($model) {
            return false; // Prevents saving
        });
        $this->bookingRepository->save($booking);

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
        $startsAtCarbonImmutable = CarbonImmutable::parse($startsAt);
        $endsAtCarbonImmutable = CarbonImmutable::parse($endsAt);
        $bookings = $this->bookingRepository->getReservationsInPeriod($startsAtCarbonImmutable, $endsAtCarbonImmutable, $roomIds,$ignoredBookingIds);
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
                3
            ],
            'multiple bookings with different periods with ignored booking with specific room' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [RoomSeeder::ROOM_6_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID],
                [1],
                3
            ],

            'multiple bookings with different periods specific Rooms' => [
                '2024-01-01 00:00:00',
                '2024-01-11 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID],
                [],
                2
            ],
            'period spans over multiple months' => [
                '2027-10-30 00:00:00',
                '2027-11-05 23:59:59',
                [],
                [],
                3
            ],
            'period spans over multiple months specific room' => [
                '2027-10-30 00:00:00',
                '2027-11-05 23:59:59',
                [RoomSeeder::ROOM_6_CAPACITY_ID],
                [],
                1,
            ],
        ];
    }

}
