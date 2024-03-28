<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int room_id
 * @property Carbon starts_at
 * @property Carbon ends_at
 */
class Booking extends Reservation
{
    use HasFactory;
    protected $table = 'bookings';
}
