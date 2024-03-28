<?php

namespace Tests\Unit\OccupancyCalculator;

use App\Services\OccupancyRateCalculator\MonthlyOccupancyRateCalculator;
use App\Services\ReservationCounter\TotalMonthlyReservationsCounter;
use App\Services\RoomCapacityRetriever;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class MonthlyOccupancyRateCalculatorTest extends TestCase
{
    private RoomCapacityRetriever $roomCapacityRetriever;
    private TotalMonthlyReservationsCounter $totalMonthlyReservationCounter;
    private MonthlyOccupancyRateCalculator $MonthlyOccupancyRateCalculator;

    //generate set up method
    protected function setUp(): void
    {
        $this->roomCapacityRetriever = $this->createMock(RoomCapacityRetriever::class);
        $this->totalMonthlyReservationCounter = $this->createMock(TotalMonthlyReservationsCounter::class);

        $this->MonthlyOccupancyRateCalculator = new MonthlyOccupancyRateCalculator(
            $this->roomCapacityRetriever,
            $this->totalMonthlyReservationCounter
        );
    }

    /**
     * @dataProvider occupancyDataProvider
     */
    public function testCalculate(int $totalCapacity, int $totalBookings, int $totalBlocks, float $expectedResult): void
    {
        $this->roomCapacityRetriever->expects($this->once())
            ->method('getMonthlyCapacity')
            ->willReturn($totalCapacity);

        $this->totalMonthlyReservationCounter->expects($this->once())
            ->method('countBookings')
            ->willReturn($totalBookings);

        $this->totalMonthlyReservationCounter->expects($this->once())
            ->method('countBlocks')
            ->willReturn($totalBlocks);

        $result = $this->MonthlyOccupancyRateCalculator->calculate(new CarbonImmutable(), []);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculateThrowsLogicException(): void
    {
        $this->roomCapacityRetriever->expects($this->once())
            ->method('getMonthlyCapacity')
            ->willReturn(12);

        $this->totalMonthlyReservationCounter->expects($this->once())
            ->method('countBookings')
            ->willReturn(0);

        $this->totalMonthlyReservationCounter->expects($this->once())
            ->method('countBlocks')
            ->willReturn(13);


        $this->expectException(\LogicException::class);

        $this->MonthlyOccupancyRateCalculator->calculate(new CarbonImmutable(), []);
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
