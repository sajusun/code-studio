<?php

namespace App\Modules\Reservation\Services;

use App\Modules\Reservation\DTOs\AvailabilitySearchData;
use App\Modules\Reservation\DTOs\CreateReservationData;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Repositories\Contracts\ReservationRepositoryInterface;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use App\Modules\Shared\Exceptions\ReservationConflictException;
use App\Modules\Shared\Exceptions\RoomNotAvailableException;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly RoomRepositoryInterface $rooms,
    ) {}

    /**
     * @return Collection<int, \App\Modules\Room\Models\Room>
     */
    public function searchAvailability(AvailabilitySearchData $search): Collection
    {
        $this->assertValidDateRange($search->checkInDate, $search->checkOutDate);

        return $this->rooms->findAvailableForDateRange(
            $search->checkInDate,
            $search->checkOutDate,
            $search->roomTypeId,
        );
    }

    public function isRoomAvailable(
        int $roomId,
        CarbonInterface $checkIn,
        CarbonInterface $checkOut,
        ?int $excludeReservationId = null,
    ): bool {
        $room = $this->rooms->findByIdOrFail($roomId);

        if ($room->status === RoomStatus::Maintenance) {
            return false;
        }

        return ! $this->reservations->hasOverlappingBooking(
            $roomId,
            $checkIn,
            $checkOut,
            $excludeReservationId,
        );
    }

    public function createReservation(CreateReservationData $data): Reservation
    {
        $this->assertValidDateRange($data->checkInDate, $data->checkOutDate);

        if (! $this->isRoomAvailable($data->roomId, $data->checkInDate, $data->checkOutDate)) {
            throw new RoomNotAvailableException($data->roomId);
        }

        return DB::transaction(function () use ($data) {
            if ($this->reservations->hasOverlappingBooking(
                $data->roomId,
                $data->checkInDate,
                $data->checkOutDate,
            )) {
                throw new ReservationConflictException;
            }

            $room = $this->rooms->findByIdOrFail($data->roomId);
            $nights = $this->calculateNights($data->checkInDate, $data->checkOutDate);
            $nightlyRate = (float) $room->roomType->base_rate;
            $estimatedTotal = round($nights * $nightlyRate, 2);

            $reservation = $this->reservations->create([
                'reference' => $this->generateReference(),
                'room_id' => $data->roomId,
                'guest_id' => $data->guestId,
                'check_in_date' => $data->checkInDate->toDateString(),
                'check_out_date' => $data->checkOutDate->toDateString(),
                'guests_count' => $data->guestsCount,
                'status' => $data->status ?? ReservationStatus::Confirmed,
                'nightly_rate' => $nightlyRate,
                'estimated_total' => $estimatedTotal,
                'payment_status' => 'pending',
                'payment_method' => $data->paymentMethod ?? null,
            ]);

            $this->rooms->updateStatus($data->roomId, RoomStatus::Reserved);

            return $this->reservations->findByIdOrFail($reservation->id);
        });
    }

    public function cancelReservation(int $reservationId): Reservation
    {
        return DB::transaction(function () use ($reservationId) {
            $reservation = $this->reservations->findByIdOrFail($reservationId);

            if ($reservation->status === ReservationStatus::Cancelled) {
                return $reservation;
            }

            if ($reservation->status === ReservationStatus::Completed) {
                throw new ReservationConflictException('Cannot cancel a completed reservation.');
            }

            $reservation->update([
                'status' => ReservationStatus::Cancelled,
                'cancelled_at' => now(),
            ]);

            if (! $this->reservations->hasOverlappingBooking(
                $reservation->room_id,
                $reservation->check_in_date,
                $reservation->check_out_date,
            )) {
                $this->rooms->updateStatus($reservation->room_id, RoomStatus::Available);
            }

            return $this->reservations->findByIdOrFail($reservationId);
        });
    }

    public function calculateNights(CarbonInterface $checkIn, CarbonInterface $checkOut): int
    {
        return max(1, $checkIn->diffInDays($checkOut));
    }

    private function assertValidDateRange(CarbonInterface $checkIn, CarbonInterface $checkOut): void
    {
        if ($checkOut->lte($checkIn)) {
            throw new \InvalidArgumentException('Check-out date must be after check-in date.');
        }
    }

    private function generateReference(): string
    {
        return 'RES-'.strtoupper(Str::random(8));
    }
}
