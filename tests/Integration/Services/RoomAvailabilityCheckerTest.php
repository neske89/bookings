<?php

use App\Exception\RoomIsAlreadyFullyBookedException;
use App\Services\RoomAvailabilityChecker;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityCheckerTest extends TestCase
{
    use RefreshDatabase;

    private RoomAvailabilityChecker $roomAvailabilityChecker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
        $this->roomAvailabilityChecker = app(RoomAvailabilityChecker::class);
    }

    /**
     * @dataProvider getTestCheckDataProvider
     */
    public function testCheck(
        int $roomId,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        array $bookingsToIgnore,
        bool $expectException
    ): void {
        if ($expectException) {
            $this->expectException(RoomIsAlreadyFullyBookedException::class);
            $this->expectExceptionMessage('Room is already fully booked for date');
        } else {
            $this->expectNotToPerformAssertions();
        }
        $this->roomAvailabilityChecker->check($roomId, $startsAt, $endsAt, $bookingsToIgnore);
    }

    public static function getTestCheckDataProvider(): array
    {
        //room id,start at, ends at,bookings to ignore, expect exception
        return [
            'room available' => [
                RoomSeeder::ROOM_6_CAPACITY_ID,
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                [],
                false,
            ],
            'room unavailable' => [
                RoomSeeder::ROOM_2_CAPACITY_ID,
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                [],
                true,
            ],
            'room available with ignored booking' => [
                RoomSeeder::ROOM_2_CAPACITY_ID,
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                CarbonImmutable::parse('2024-01-02 00:00:00'),
                [4],
                false,
            ],
        ];
    }


}
