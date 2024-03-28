<?php

namespace Tests\Unit\Services;

use App\Repositories\RoomRepositoryInterface;
use App\Services\RoomCapacityRetriever;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class RoomCapacityRetrieverServiceTest extends TestCase
{
    private RoomRepositoryInterface $roomRepository;
    private RoomCapacityRetriever $roomCapacityRetriever;
    //generate set up method
    protected function setUp(): void
    {
        $this->roomRepository = $this->createMock(RoomRepositoryInterface::class);
        $this->roomCapacityRetriever = new RoomCapacityRetriever(
            $this->roomRepository
        );
    }

    /**
     * @dataProvider monthlyCapacityDataProvider
     */
    public function testGetMonthlyCapacity(string $referenceDate,int $totalCapacity,int $daysInMonth,int $expectedResult): void
    {
        $this->roomRepository->expects($this->once())
            ->method('getTotalCapacity')
            ->willReturn($totalCapacity);


        $result = $this->roomCapacityRetriever->getMonthlyCapacity(CarbonImmutable::parse($referenceDate),[]);

        $this->assertEquals($expectedResult, $result);
    }

    public static function monthlyCapacityDataProvider():array
    {
        return [
            // Format: [$referenceDate,$dailyRoomCapacity, $daysInMonth, $expectedResult]
            ['2024-01-01 00:00:00',12,31, 12*31],
            ['2024-02-01 00:00:00',12,29, 12*29],
            ['2025-02-01 00:00:00',12,29, 12*28],
        ];
    }
}
