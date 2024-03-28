<?php

namespace App\Services;

use App\Repositories\RoomRepositoryInterface;
use Carbon\CarbonImmutable;


class RoomCapacityRetriever
{
    public function __construct(
        private RoomRepositoryInterface $roomRepository,
    ) {
    }

    /**
     * @param int[] $roomIds
     */
    public function getDailyCapacity(array $roomIds):int {
        return $this->roomRepository->getTotalCapacity($roomIds);
    }

    /**
     * @param int[] $roomIds
     */
    public function getMonthlyCapacity(CarbonImmutable $referenceDate, array $roomIds):int {
        return $this->getDailyCapacity($roomIds) * $referenceDate->daysInMonth;
    }

}
