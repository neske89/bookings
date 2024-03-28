<?php

namespace Tests\Integration\Repository;

use App\Repositories\BlockRepositoryInterface;
use Carbon\Carbon;
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
     * @dataProvider sumReservationsOnDateDataProvider
     */
    public function testSumReservationsOnDate(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $result = $this->blockRepository->sumReservationsOnDate(Carbon::parse($referenceDate), $roomIds);
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
