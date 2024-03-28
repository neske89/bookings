<?php

namespace Tests\Feature;

use App\Models\Room;
use Database\Seeders\AssigmentDataSeeder;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyOccupancyRatesTest extends TestCase
{
    private CONST URI = '/api/daily-occupancy-rates';
    use RefreshDatabase;

    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
    }

    /**
     * @dataProvider dailyOccupancyRatesDataProvider
     */
    public function testDailyOccupancyRatesCalculation(string $date, array $roomIds, float $expectedRate): void
    {
        $this->seed(AssigmentDataSeeder::class);
        $queryString = http_build_query([
            'room_ids' => $roomIds,
        ]);
        $response = $this->json('GET', self::URI."/{$date}?$queryString",);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'occupancy_rate',
            ])
            ->assertJson([
                'occupancy_rate' => $expectedRate,
            ]);
    }

    public function testDailyOccupancyRatesCalculationThrowsError(): void
    {
        $this->seed(ReservationsSeeder::class);
        $roomIds = [RoomSeeder::ROOM_2_CAPACITY_ID];
        $date = '2027-01-01';
        $queryString = http_build_query([
            'room_ids' => $roomIds,
        ]);
        $response = $this->json('GET', self::URI."/{$date}?$queryString");
        $response->assertStatus(500);
    }

    public static function dailyOccupancyRatesDataProvider(): array
    {
        return [
            // Format: [dateTime,roomIds, expectedResult]
            '2024-01-02' => ['2024-01-02', [], 0.36],
            '2024-01-06' => ['2024-01-06', [RoomSeeder::ROOM_4_CAPACITY_ID, RoomSeeder::ROOM_2_CAPACITY_ID], 0.2],
        ];
    }


}
