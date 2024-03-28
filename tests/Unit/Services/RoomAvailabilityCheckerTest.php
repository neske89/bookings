<?php

namespace Tests\Unit\Services;

use App\Exception\RoomIsAlreadyFullyBookedException;
use App\Models\Block;
use App\Models\Booking;
use App\Models\Room;
use App\Repositories\BlockRepositoryInterface;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\RoomRepositoryInterface;
use App\Services\RoomAvailabilityChecker;
use ArrayIterator;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoomAvailabilityCheckerTest extends TestCase
{
    private BookingRepositoryInterface $bookingRepository;
    private BlockRepositoryInterface $blockRepository;
    private RoomRepositoryInterface $roomRepository;

    protected function setUp(): void
    {
        $this->bookingRepository = $this->createMock(BookingRepositoryInterface::class);
        $this->blockRepository = $this->createMock(BlockRepositoryInterface::class);
        $this->roomRepository = $this->createMock(RoomRepositoryInterface::class);
    }


    /**
     * @dataProvider checkDataProvider
     */
    public function testCheck(
        int $capacity,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        array $bookings,
        array $blocks,
        bool $expectedException
    ): void {
        $checker = new RoomAvailabilityChecker($this->bookingRepository, $this->blockRepository, $this->roomRepository);
        $bookingMocks = [];
        $blockMocks = [];
        foreach ($bookings as $booking) {
            $bookingMocks[] = $this->generateBookingMock($booking);
        }
        foreach ($blocks as $block) {
            $blockMocks[] = $this->generateBlockMock($block);
        }

        $lazyBookingsCollection = $this->createMock(LazyCollection::class);
        $lazyBlocksCollection = $this->createMock(LazyCollection::class);
        $lazyBookingsCollection->method('getIterator')->willReturn(new ArrayIterator($bookingMocks));
        $lazyBlocksCollection->method('getIterator')->willReturn(new ArrayIterator($blockMocks));

        $this->roomRepository->method('findByIdOrFail')
            ->willReturn($this->generateRoomMock($capacity));

        $this->bookingRepository->method('getReservationsInPeriod')
            ->willReturn($lazyBookingsCollection);

        $this->blockRepository->method('getReservationsInPeriod')
            ->willReturn($lazyBlocksCollection);

        if ($expectedException) {
            $this->expectException(RoomIsAlreadyFullyBookedException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }

        $checker->check($capacity, $startsAt, $endsAt);
    }

    public function testCheckWillThrowModelNotFoundException(): void
    {
        $checker = new RoomAvailabilityChecker($this->bookingRepository, $this->blockRepository, $this->roomRepository);
        $this->roomRepository->method('findByIdOrFail')
            ->willThrowException(new ModelNotFoundException());
        $this->expectException(ModelNotFoundException::class);
        $checker->check(1, CarbonImmutable::now(), CarbonImmutable::now());
    }

    public static function checkDataProvider(): array
    {
        //capacity, startsAt, endsAt, bookings, blocks, expectedException
        return [
            'Room is available' => [
                3,
                CarbonImmutable::parse('2024-01-05 00:00:00'),
                CarbonImmutable::parse('2024-01-08 00:00:00'),
                [

                ],
                [],
                false,
            ],
            'Room is booked by bookings' => [
                3,
                CarbonImmutable::parse('2024-01-05 00:00:00'),
                CarbonImmutable::parse('2024-01-08 00:00:00'),
                [
                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],
                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],
                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],
                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],
                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],
                ],
                [],
                true,
            ],
            'Room is booked by blocks' => [
                3,
                CarbonImmutable::parse('2024-01-05 00:00:00'),
                CarbonImmutable::parse('2024-01-08 00:00:00'),
                [

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],
                ],
                [

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],
                ],
                true,
            ],
            'Room is booked by bookings & blocks' => [
                3,
                CarbonImmutable::parse('2024-01-05 00:00:00'),
                CarbonImmutable::parse('2024-01-08 00:00:00'),
                [

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],
                ],
                [

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-05 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-06 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],

                    ['room_id' => 1, 'starts_at' => '2024-01-01 00:00:00', 'ends_at' => '2024-01-07 23:59:59'],
                ],
                true,
            ],

        ];
    }

    private function generateBookingMock(array $data): MockObject
    {
        $booking = $this->createMock(Booking::class);
        $startsAt = CarbonImmutable::parse($data['starts_at']);
        $endsAt = CarbonImmutable::parse($data['ends_at']);
        $booking->method('getRoomId')->willReturn($data['room_id']);
        $booking->method('getStartsAt')->willReturn($startsAt);
        $booking->method('getEndsAt')->willReturn($endsAt);

        return $booking;
    }

    private function generateBlockMock(array $data): MockObject
    {
        $block = $this->createMock(Block::class);
        $startsAt = CarbonImmutable::parse($data['starts_at']);
        $endsAt = CarbonImmutable::parse($data['ends_at']);
        $block->method('getRoomId')->willReturn($data['room_id']);
        $block->method('getStartsAt')->willReturn($startsAt);
        $block->method('getEndsAt')->willReturn($endsAt);

        return $block;
    }

    private function generateRoomMock(int $capacity): MockObject
    {
        $room = $this->createMock(Room::class);
        $room->method('getCapacity')->willReturn($capacity);
        return $room;
    }
}
