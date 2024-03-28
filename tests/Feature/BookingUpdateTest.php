<?php

namespace Tests\Feature;

use App\Models\Room;
use Database\Seeders\AssigmentDataSeeder;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingUpdateTest extends TestCase
{
    use RefreshDatabase;

    private const URI = '/api/booking';

    //generate set up method
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    public function testBookingUpdate(): void
    {
        $response = $this->postJson(BookingCreateTest::URI, [
            'room_id' => RoomSeeder::ROOM_6_CAPACITY_ID,
            'starts_at' => '2024-01-01',
            'ends_at' => '2024-01-01',
        ]);

        $bookingId = $response->json('id');
        $response = $this->putJson(self::URI."/$bookingId", [
            'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID,
            'starts_at' => '2028-01-01',
            'ends_at' => '2028-01-01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'roomId' => RoomSeeder::ROOM_2_CAPACITY_ID,
                'startsAt' => '2028-01-01T00:00:00.000000Z',
                'endsAt' => '2028-01-01T23:59:59.000000Z',
                'id' => $bookingId,
            ]);
    }

    public function testBookingUpdateAffectsOccupancy(): void
    {
        $dailyResponse = $this->json('GET', DailyOccupancyRatesTest::URI."/2024-01-02",);
        $monthlyResponse = $this->json('GET', MonthlyOccupancyRatesTest::URI."/2024-01");

        $dailyOccupancy = $dailyResponse->json('occupancy_rate');
        $monthlyOccupancy = $monthlyResponse->json('occupancy_rate');

        $bookingId = 1;
        $updateResponse = $this->putJson(self::URI."/$bookingId", [
            'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID,
            'starts_at' => '2028-01-01',
            'ends_at' => '2028-01-01',
        ]);
        $updateResponse->assertStatus(200);
        //updated occupancies

        $newDailyResponse = $this->json('GET', DailyOccupancyRatesTest::URI."/2024-01-02",);
        $newMonthlyResponse = $this->json('GET', MonthlyOccupancyRatesTest::URI."/2024-01");
        $updatedDailyOccupancy = $newDailyResponse->json('occupancy_rate');
        $updatedMonthlyOccupancy = $newMonthlyResponse->json('occupancy_rate');

        $this->assertNotEquals($dailyOccupancy, $updatedDailyOccupancy);
        $this->assertNotEquals($monthlyOccupancy, $updatedMonthlyOccupancy);
    }

    public function testBookingUpdateThrowsRoomOccupiedException(): void
    {

        $bookingId = 1;
         $response = $this->putJson(self::URI."/$bookingId", [
             'room_id' => RoomSeeder::ROOM_2_CAPACITY_ID,
             'starts_at' => '2024-01-04',
             'ends_at' => '2024-01-05',
         ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Room is already fully booked for date 2024-01-04',
            ]);
    }
    public function testBookingUpdateThrowsRoomNotFoundException(): void
    {

        $bookingId = 1;
        $response = $this->putJson(self::URI."/$bookingId", [
            'room_id' => 100,
            'starts_at' => '2024-01-04',
            'ends_at' => '2024-01-05',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Room] 100',
            ]);
    }
}

