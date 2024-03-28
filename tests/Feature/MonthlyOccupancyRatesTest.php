<?php

namespace Tests\Feature;

use App\Models\Room;
use Database\Seeders\AssigmentDataSeeder;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyOccupancyRatesTest extends TestCase
{
    use RefreshDatabase;
    private CONST URI = '/api/monthly-occupancy-rates';
    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
    }

    /**
     * @dataProvider monthlyOccupancyRatesDataProvider
     */
    public function testMonthlyOccupancyRatesCalculation(string $date, array $roomIds, float $expectedRate): void
    {
        $this->seed(AssigmentDataSeeder::class);
        $queryString = http_build_query([
            'room_ids' => $roomIds,
        ]);
        $response = $this->json('GET', self::URI."/{$date}?$queryString");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'occupancy_rate',
            ])
            ->assertJson([
                'occupancy_rate' => $expectedRate,
            ]);
    }


    public function testMonthlyOccupancyRatesCalculationThrowsError(): void
    {
        $this->seed(ReservationsSeeder::class);
        $roomIds = [RoomSeeder::ROOM_2_CAPACITY_ID];
        $date = '2028-02';
        $queryString = http_build_query([
            'room_ids' => $roomIds,
        ]);
        $response = $this->json('GET', self::URI."/{$date}?$queryString");
        $response->assertStatus(500);
    }

    public static function monthlyOccupancyRatesDataProvider(): array
    {
        return [
            // Format: [dateTime,roomIds, expectedResult]
            '2024-01 Whole month' => ['2024-01', [], 0.07],
            '2024-01 Specific Rooms' => ['2024-01', [RoomSeeder::ROOM_4_CAPACITY_ID, RoomSeeder::ROOM_2_CAPACITY_ID], 0.06],
        ];
    }


}
