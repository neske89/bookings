<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int room_id
 * @property Carbon starts_at
 * @property Carbon ends_at
 */
abstract class Reservation extends AbstractModel
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'room_id' => 'integer',
    ];
    protected $fillable = ['room_id', 'starts_at', 'ends_at'];


    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
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

    public function getStartsAt(): ?Carbon
    {
        return $this->starts_at;
    }

    public function setStartsAt(?Carbon $starts_at): self
    {
        $this->starts_at = $starts_at;

        return $this;
    }

    public function getEndsAt(): ?Carbon
    {
        return $this->ends_at;
    }

    public function setEndsAt(?Carbon $ends_at): self
    {
        $this->ends_at = $ends_at;

        return $this;
    }
}
