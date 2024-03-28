<?php

namespace OccupancyRateCalculator;

use App\Services\OccupancyRateCalculator\MonthlyOccupancyRateCalculator;

use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyOccupancyRateCalculatorTest extends TestCase
{
    use RefreshDatabase;
    private MonthlyOccupancyRateCalculator $monthlyOccupancyRateCalculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
        $this->monthlyOccupancyRateCalculator = app(MonthlyOccupancyRateCalculator::class);
    }

    /**
     * @dataProvider occupancyDataProvider
     */
    public function testCalculate(string $referenceDate, array $roomIds,float $expectedResult): void
    {
        $result = $this->monthlyOccupancyRateCalculator->calculate(CarbonImmutable::parse($referenceDate), $roomIds);
        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculateThrowsLogicException():void {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Total capacity cannot be less than total blocks');
        $this->monthlyOccupancyRateCalculator->calculate(CarbonImmutable::parse('2028-02-01 00:00:00'), [RoomSeeder::ROOM_2_CAPACITY_ID]);
    }

    public static function occupancyDataProvider():array
    {
        return [
            // Format: [dateTime,roomIds, expectedResult]
            'all rooms January'=>['2024-01-01 00:00:00', [],  0.08],
            'specific rooms January'=>['2024-01-01 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID],  0.1],
            'booking spanning over two months - in start month - specific room'=>['2029-04-01 00:00:00',[RoomSeeder::ROOM_4_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID],0.11],
            'booking spanning over two months - in end month - specific room'=>['2029-05-01 00:00:00',[RoomSeeder::ROOM_4_CAPACITY_ID,RoomSeeder::ROOM_2_CAPACITY_ID],0.05],
            'room is fully occupied - booking start before and ends after a month' => ['2027-11-03 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 1],
            'room is fully occupied - in concrete month' => ['2027-11-03 00:00:00', [RoomSeeder::ROOM_2_CAPACITY_ID], 1],
            'room does not exist' => ['2026-12-03 00:00:00', [100], 0],
            'room does not have bookings'=>['2027-12-03 00:00:00', [RoomSeeder::ROOM_4_CAPACITY_ID], 0],

        ];
    }
}
