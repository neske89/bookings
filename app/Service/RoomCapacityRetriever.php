<?php

namespace App\Service;

use App\Repository\RoomRepositoryInterface;
use Carbon\Carbon;

class RoomCapacityRetriever
{
    //generate constructor
    public function __construct(
        private RoomRepositoryInterface $roomRepository,
    ) {
    }

    /**
     * @param int[] $roomIds
     * @return void
     */
    public function getDailyCapacity(array $roomIds):int {
        return $this->roomRepository->getTotalCapacity($roomIds);
    }

    public function getMonthlyCapacity(Carbon $referenceDate, array $roomIds):int {
        return $this->roomRepository->getTotalCapacity($roomIds) * $referenceDate->daysInMonth;
    }

}
