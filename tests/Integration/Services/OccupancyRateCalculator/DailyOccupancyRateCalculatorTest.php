<?php

namespace OccupancyRateCalculator;

use App\Services\OccupancyRateCalculator\DailyOccupancyRateCalculator;
use Carbon\Carbon;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyOccupancyRateCalculatorTest extends TestCase
{
    use RefreshDatabase;
    private DailyOccupancyRateCalculator $dailyOccupancyRateCalculator;

    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
        $this->dailyOccupancyRateCalculator = app(DailyOccupancyRateCalculator::class);
    }

    /**
     * @dataProvider occupancyDataProvider
     */
    public function testCalculate(string $referenceDate, array $roomIds,float $expectedResult): void
    {
        $result = $this->dailyOccupancyRateCalculator->calculate(Carbon::parse($referenceDate), $roomIds);
        $this->assertEquals($expectedResult, $result);
    }
    public static function occupancyDataProvider():array
    {
        return [
            // Format: [dateTime,roomIds, expectedResult]
            'all rooms'=>['2027-10-03 00:00:00', [],  0.25],
            'specific room'=>['2027-10-03 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID],  0.17],
            'specific rooms'=>['2027-10-03 00:00:00', [RoomSeeder::ROOM_6_CAPACITY_ID,RoomSeeder::ROOM_4_CAPACITY_ID],  0.1],
            'specific room 0'=>['2024-01-02 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID],  0],
            'room does not exist' => ['2026-12-03 00:00:00', [100], 0],
            'room does not have bookings'=>['2027-12-03 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID], 0],
            'room completely blocked'=>['2026-01-01 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 0],
        ];
    }
    public function testCalculateThrowsLogicException():void {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Total capacity cannot be less than total blocks');
        $this->dailyOccupancyRateCalculator->calculate(Carbon::parse('2027-01-01 00:00:00'), [RoomSeeder::ROOM_2_CAPACITY_ID]);
    }


}
