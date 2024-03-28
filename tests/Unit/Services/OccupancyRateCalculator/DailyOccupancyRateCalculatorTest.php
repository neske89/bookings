<?php

namespace Tests\Unit\OccupancyCalculator;

use App\Services\OccupancyRateCalculator\DailyOccupancyRateCalculator;
use App\Services\ReservationCounter\TotalDailyReservationsCounter;
use App\Services\RoomCapacityRetriever;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class DailyOccupancyRateCalculatorTest extends TestCase
{
    private RoomCapacityRetriever $roomCapacityRetriever;
    private TotalDailyReservationsCounter $totalDailyReservationCounter;
    private DailyOccupancyRateCalculator $dailyOccupancyRateCalculator;

    //generate set up method
    protected function setUp(): void
    {
        $this->roomCapacityRetriever = $this->createMock(RoomCapacityRetriever::class);
        $this->totalDailyReservationCounter = $this->createMock(TotalDailyReservationsCounter::class);

        $this->dailyOccupancyRateCalculator = new DailyOccupancyRateCalculator(
            $this->roomCapacityRetriever,
            $this->totalDailyReservationCounter,
        );
    }

    /**
     * @dataProvider occupancyDataProvider
     */
    public function testCalculate(int $totalCapacity, int $totalBookings, int $totalBlocks, float $expectedResult): void
    {
        $this->roomCapacityRetriever->expects($this->once())
            ->method('getDailyCapacity')
            ->willReturn($totalCapacity);

        $this->totalDailyReservationCounter->expects($this->once())
            ->method('countBookings')
            ->willReturn($totalBookings);

        $this->totalDailyReservationCounter->expects($this->once())
            ->method('countBlocks')
            ->willReturn($totalBlocks);

        $result = $this->dailyOccupancyRateCalculator->calculate(new Carbon(), []);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculateThrowsLogicException(): void
    {
        $this->roomCapacityRetriever->expects($this->once())
            ->method('getDailyCapacity')
            ->willReturn(12);

        $this->totalDailyReservationCounter->expects($this->once())
            ->method('countBookings')
            ->willReturn(0);

        $this->totalDailyReservationCounter->expects($this->once())
            ->method('countBlocks')
            ->willReturn(13);


        $this->expectException(\LogicException::class);

        $this->dailyOccupancyRateCalculator->calculate(new Carbon(), []);
    }

    public static function occupancyDataProvider(): array
    {
        return [
            // Format: [totalCapacity, totalBookings, totalBlocks, expectedResult]
            '0.36' => [12, 4, 1, 0.36],
            '0.2' => [6, 1, 1, 0.2],
            '1' => [12, 12, 0, 1.00],
            'total capacity = total blocks ' => [12, 0, 12, 0],
            'capacity = 0' => [0, 0, 0, 0],
        ];
    }
}
