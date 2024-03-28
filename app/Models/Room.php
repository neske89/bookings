<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $capacity
 */
class Room
{
    use HasFactory;

    protected $fillable = ['capacity'];
    protected $casts = [
        'id' => 'integer',
        'capacity' => 'integer'
    ];



    public function getCapacity(): int
    {
        return $this->capacity;
    }
    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

}
