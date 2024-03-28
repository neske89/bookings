<?php


use App\Service\RoomCapacityRetriever;
use Carbon\Carbon;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCapacityRetrieverServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoomCapacityRetriever $roomCapacityRetriever;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->roomCapacityRetriever = app(RoomCapacityRetriever::class);
    }

    /**
     * @dataProvider getDailyCapacityDataProvider
     */
    public function testGetDailyCapacity(array $roomIds, int $expectedCapacity): void
    {
        $result = $this->roomCapacityRetriever->getDailyCapacity($roomIds);
        $this->assertEquals($expectedCapacity, $result);
    }

    public static function getDailyCapacityDataProvider(): array
    {
        //@see RoomSeeder for capacities and ids
        return [
            'all rooms' => [[], 12],
            'multiple rooms' => [[RoomSeeder::ROOM_6_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID, ], 10],
            'not existing room' => [[10], 0],
        ];
    }

    /**
     * @dataProvider monthlyCapacityDataProvider
     */
    public function testGetMonthlyCapacity(string $referenceDate, array $roomIds, int $expectedResult): void
    {
        $result = $this->roomCapacityRetriever->getMonthlyCapacity(Carbon::parse($referenceDate), $roomIds);

        $this->assertEquals($expectedResult, $result);
    }

    public static function monthlyCapacityDataProvider(): array
    {
        return [
            // Format: [$referenceDate,$dailyRoomCapacity, $daysInMonth, $expectedResult]
            'January 2024 - All Rooms' => ['2024-01-01 00:00:00', [], 12 * 31],
            'February 2024 - Specific Rooms' => [
                '2024-02-01 00:00:00',
                [RoomSeeder::ROOM_6_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID],
                10 * 29,
            ],
            'February 2025 - Specific Rooms' => ['2025-02-01 00:00:00',
                [RoomSeeder::ROOM_6_CAPACITY_ID, RoomSeeder::ROOM_4_CAPACITY_ID, RoomSeeder::ROOM_2_CAPACITY_ID],
                12 * 28],
        ];
    }


}
