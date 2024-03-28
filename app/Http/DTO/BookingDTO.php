<?php

namespace App\Http\DTO;

use Carbon\CarbonImmutable;

readonly class BookingDTO
{
    public int $roomId;
    public CarbonImmutable $startsAt;
    public CarbonImmutable $endsAt;

    public function __construct(int $roomId, CarbonImmutable $startsAt, CarbonImmutable $endsAt)
    {
        $this->roomId = $roomId;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }
}
