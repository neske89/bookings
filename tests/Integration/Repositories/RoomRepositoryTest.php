<?php

namespace Tests\Integration\Repository;

use App\Repositories\RoomRepositoryInterface;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private RoomRepositoryInterface $roomRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roomRepository = app(RoomRepositoryInterface::class);
        $this->seed(RoomSeeder::class);
    }

    /**
     * @dataProvider getTotalCapacityDataProvider
     */
    public function testGetTotalCapacity(array $roomIds, int $expectedResult): void
    {
        $result = $this->roomRepository->getTotalCapacity($roomIds);
        $this->assertEquals($expectedResult, $result);
    }

    public static function getTotalCapacityDataProvider(): array
    {
        return [
            'All Rooms' => [[], 12],
            'Room A' => [[RoomSeeder::ROOM_6_CAPACITY_ID], 6],
            'Room A + Not existing' => [[RoomSeeder::ROOM_6_CAPACITY_ID, 100], 6],
            'Room A + Room B' => [[RoomSeeder::ROOM_6_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID], 10],
            'Room A + Room B + Room C' => [
                [
                    RoomSeeder::ROOM_6_CAPACITY_ID,
                    RoomSeeder::ROOM_4_CAPACITY_ID,
                    RoomSeeder::ROOM_2_CAPACITY_ID,
                ],
                12,
            ],
            'Not Existing Room' => [[10], 0],
        ];
    }
}
