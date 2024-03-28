<?php

namespace Tests\Unit\Services;

use App\Services\DaysIntervalCalculator;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class DaysIntervalCalculatorTest extends TestCase
{
    /**
     * @dataProvider dateIntervalProvider
     */
    public function testCalculate(string $start, string $end, int $expectedResult): void
    {
        $service = new DaysIntervalCalculator();
        $result = $service->calculate(new CarbonImmutable($start), new CarbonImmutable($end));
        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculateThrowsLogicException(): void
    {
        $service = new DaysIntervalCalculator();
        $this->expectException(\LogicException::class);
        $service->calculate(new CarbonImmutable('2024-04-01 00:00:00'), new CarbonImmutable('2024-03-31 23:59:59'));
    }

    public static function dateIntervalProvider(): array
    {
        return [
            'duration is one month' => ['2024-02-01 00:00:00', '2024-02-28 23:59:59', 28],
            'dates are in different months' => ['2024-02-20 23:59:59', '2024-03-10 23:59:59', 20],
            'more than one month' => ['2024-01-01 00:00:00', '2024-03-31 23:59:59', 91],
            '5 days' => ['2024-01-01 00:00:00', '2024-01-05 23:59:59', 5],
            '6 days' => ['2024-01-03 00:00:00', '2024-01-08 23:59:59', 6],
            'ten days' => ['2024-01-01 00:00:00', '2024-01-10 23:59:59', 10],
        ];
    }
}
