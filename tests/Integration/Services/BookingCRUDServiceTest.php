<?php


use App\Http\DTO\BookingDTO;
use App\Exception\RoomIsAlreadyFullyBookedException;
use App\Repositories\BookingRepositoryInterface;
use App\Services\BookingCRUDService;
use Carbon\CarbonImmutable;
use Database\Seeders\ReservationsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCRUDServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingCRUDService $bookingService;
    private BookingRepositoryInterface $bookingRepository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->bookingService = app(BookingCRUDService::class);
        $this->bookingRepository = app(BookingRepositoryInterface::class);

        $this->seed(RoomSeeder::class);
        $this->seed(ReservationsSeeder::class);
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(BookingDTO $bookingDTO, ?string $exceptionClass,?string $exceptionMessage): void
    {
        if ($exceptionClass) {
            $this->expectException($exceptionClass);
            $this->expectExceptionMessage($exceptionMessage);
        }
        $this->bookingService->create($bookingDTO);

        if (!$exceptionClass) {
            $this->assertDatabaseHas('bookings', [
                'room_id' => $bookingDTO->roomId,
                'starts_at' => $bookingDTO->startsAt,
                'ends_at' => $bookingDTO->endsAt,
            ]);
        }
    }

    public static function createDataProvider(): array
    {
       return [
            'booking is created' => [
                new BookingDTO(
                    RoomSeeder::ROOM_6_CAPACITY_ID,
                    CarbonImmutable::parse('2024-01-02 00:00:00'),
                    CarbonImmutable::parse('2024-01-03 00:00:00'),
                ),
                null,
                null
            ],
           'room is occupied' => [
               new BookingDTO(
                   RoomSeeder::ROOM_2_CAPACITY_ID,
                   CarbonImmutable::parse('2024-01-02 00:00:00'),
                   CarbonImmutable::parse('2024-01-02 00:00:00'),
               ),
               RoomIsAlreadyFullyBookedException::class,
               'Room is already fully booked for date'
           ],
           'room not found' => [
               new BookingDTO(
                   20,
                   CarbonImmutable::parse('2024-01-02 00:00:00'),
                   CarbonImmutable::parse('2024-01-02 00:00:00'),
               ),
               ModelNotFoundException::class,
               'No query results for model [App\Models\Room]'
           ],
        ];
    }

    /**
     * @dataProvider updateDataProvider
     */
    public function testUpdate(int $bookingId,BookingDTO $bookingDTO, ?string $exceptionClass,?string $exceptionMessage):void
    {
        $booking = $this->bookingRepository->getByIdOrFail($bookingId);
        if ($exceptionClass) {
            $this->expectException($exceptionClass);
            $this->expectExceptionMessage($exceptionMessage);
        }

        $this->bookingService->update($booking,$bookingDTO);

        if (!$exceptionClass) {
            $this->assertEquals($bookingDTO->roomId, $booking->room_id);
            $this->assertEquals($bookingDTO->startsAt, $booking->starts_at);
            $this->assertEquals($bookingDTO->endsAt, $booking->ends_at);
            $this->assertTrue($booking->isClean());
        }
    }

    public static function updateDataProvider(): array
    {
        return [
            'booking is updated' => [
                1,
                new BookingDTO(
                    RoomSeeder::ROOM_2_CAPACITY_ID,
                    CarbonImmutable::parse('2025-01-04 00:00:00'),
                    CarbonImmutable::parse('2025-01-05 00:00:00'),
                ),
                null,
                null
            ],
            'booking is updated - constraint was current booking' => [
                9,
                new BookingDTO(
                    RoomSeeder::ROOM_2_CAPACITY_ID,
                    CarbonImmutable::parse('2026-12-04 00:00:00'),
                    CarbonImmutable::parse('2026-12-05 23:59:59'),
                ),
                null,
                null
            ],
            'room is occupied' => [
                1,
                new BookingDTO(
                    RoomSeeder::ROOM_2_CAPACITY_ID,
                    CarbonImmutable::parse('2024-01-04 00:00:00'),
                    CarbonImmutable::parse('2024-01-05 00:00:00'),
                ),
                RoomIsAlreadyFullyBookedException::class,
                'Room is already fully booked for date'
            ],

            'room not found' => [
                1,
                new BookingDTO(
                    20,
                    CarbonImmutable::parse('2024-01-02 00:00:00'),
                    CarbonImmutable::parse('2024-01-02 00:00:00'),
                ),
                ModelNotFoundException::class,
                'No query results for model [App\Models\Room]'
            ],
        ];
    }
}
