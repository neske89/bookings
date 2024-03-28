<?php

namespace App\Models;


use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int room_id
 * @property CarbonImmutable starts_at
 * @property CarbonImmutable ends_at
 */
abstract class Reservation extends AbstractModel
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'room_id' => 'integer',
    ];
    protected $fillable = ['room_id', 'starts_at', 'ends_at'];


    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): self
    {
        $this->room_id = $room_id;

        return $this;
    }

    public function getStartsAt(): ?CarbonImmutable
    {
        return $this->starts_at;
    }

    public function setStartsAt(?CarbonImmutable $starts_at): self
    {
        $this->starts_at = $starts_at;

        return $this;
    }

    public function getEndsAt(): ?CarbonImmutable
    {
        return $this->ends_at;
    }

    public function setEndsAt(?CarbonImmutable $ends_at): self
    {
        $this->ends_at = $ends_at;

        return $this;
    }
}
