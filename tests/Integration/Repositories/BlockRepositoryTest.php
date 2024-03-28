<?php

namespace Tests\Integration\Repository;

use App\Repositories\BlockRepositoryInterface;

use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private BlockRepositoryInterface $blockRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blockRepository = app(BlockRepositoryInterface::class);
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
        int $expectedResult
    ): void {
        $startsAtCarbon = CarbonImmutable::parse($startsAt);
        $endsAtCarbon = CarbonImmutable::parse($endsAt);
        $blocks = $this->blockRepository->getReservationsInPeriod($startsAtCarbon, $endsAtCarbon, $roomIds);
        $this->assertEquals($expectedResult, $blocks->count());
    }

    public static function getReservationsInPeriodDataProvider(): array
    {
        return [
            'multiple blocks with different periods' => [
                '2024-01-01 00:00:00',
                '2026-01-11 23:59:59',
                [],
                5,
            ],
            'multiple bookings with different periods specific Rooms' => [
                '2024-01-01 00:00:00',
                '2026-01-11 23:59:59',
                [RoomSeeder::ROOM_2_CAPACITY_ID],
                3,
            ],
        ];
    }

    /**
     * @dataProvider sumReservationsOnDateDataProvider
     */
    public function testSumReservationsOnDate(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $result = $this->blockRepository->sumReservationsOnDate(CarbonImmutable::parse($referenceDate), $roomIds);
        $this->assertEquals($expectedResult, $result);
    }

    public static function sumReservationsOnDateDataProvider(): array
    {
        return [
            'on Date all rooms' => ['2024-01-01 00:00:00',[] , 3],
            'on Date specific room' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID], 2],
            'on Date specific rooms' => ['2024-01-01 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID], 3],
        ];
    }
}
