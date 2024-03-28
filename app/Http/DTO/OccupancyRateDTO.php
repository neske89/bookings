<?php

namespace App\Http\DTO;

class OccupancyRateDTO implements \JsonSerializable
{
    public $occupancyRate;

    public function __construct(float $occupancyRate)
    {
        $this->occupancyRate = $occupancyRate;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'occupancy_rate' => $this->occupancyRate,
        ];
    }
}
