<?php

namespace Tests\Feature;

use App\Models\Room;
use Database\Seeders\AssigmentDataSeeder;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCreateTest extends TestCase
{
    use RefreshDatabase;

    public const URI = '/api/booking';

    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    public function testBookingCreate(): void
    {
        $response = $this->postJson(self::URI, [
            'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID,
            'starts_at' => '2024-01-01',
            'ends_at' => '2024-01-03',
        ]);
        $response->assertStatus(201)
            ->assertJson([
                'roomId' => '1',
                'startsAt' => '2024-01-01T00:00:00.000000Z',
                'endsAt' => '2024-01-03T23:59:59.000000Z',
            ]);

    }

    public function testBookingCreateThrowsRoomOccupiedException(): void
    {
        $response = $this->postJson(self::URI, [
            'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID,
            'starts_at' => '2024-01-02',
            'ends_at' => '2024-01-03',
        ]);
        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Room is already fully booked for date 2024-01-02',
            ]);
    }
}

