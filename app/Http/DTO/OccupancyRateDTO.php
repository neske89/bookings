<?php

namespace App\Http\DTO;

readonly class OccupancyRateDTO implements \JsonSerializable
{

    public function __construct(public float $occupancyRate)
    {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'occupancy_rate' => $this->occupancyRate,
        ];
    }
}
