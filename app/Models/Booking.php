<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Reservation
{
    use HasFactory;
    protected $table = 'bookings';
}
