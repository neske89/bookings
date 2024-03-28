<?php

namespace App\Http\Controllers;

use App\Http\DTO\BookingDTO;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Repositories\BookingRepositoryInterface;
use App\Services\BookingCRUDService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingCRUDService $bookingCRUDService): JSONResponse
    {
        $validated = $request->validated();

        // Create the BookingDTO with the validated data.
        $bookingDTO = new BookingDTO(
            roomId: $validated['room_id'],
            startsAt: new CarbonImmutable($validated['starts_at']),
            endsAt: new CarbonImmutable($validated['ends_at'])
        );

        $createdBookingDTO = $bookingCRUDService->create($bookingDTO);

        return response()->json($createdBookingDTO, 201);
    }

    public function update(
        Booking $id,
        UpdateBookingRequest $request,
        BookingCRUDService $bookingCRUDService,
    ): JSONResponse {
        $validated = $request->validated();
        //variable name is more semnantically correct
        $booking = $id;
        // Create the BookingDTO with the validated data.
        $bookingDTO = new BookingDTO(
            roomId: $validated['room_id'],
            startsAt: new CarbonImmutable($validated['starts_at']),
            endsAt: new CarbonImmutable($validated['ends_at'])
        );

        $updatedBookingDTO = $bookingCRUDService->update($booking,$bookingDTO);

        return response()->json($updatedBookingDTO, 200);
    }
}
