<?php

namespace App\Modules\Stay\Services;

use App\Modules\Billing\Services\BillingService;
use App\Modules\Reservation\Repositories\Contracts\ReservationRepositoryInterface;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use App\Modules\Shared\Enums\StayStatus;
use App\Modules\Shared\Exceptions\InvalidStayStateException;
use App\Modules\Stay\Models\Stay;
use App\Modules\Stay\Repositories\Contracts\StayRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StayService
{
    public function __construct(
        private readonly StayRepositoryInterface $stays,
        private readonly ReservationRepositoryInterface $reservations,
        private readonly RoomRepositoryInterface $rooms,
        private readonly BillingService $billing,
    ) {}

    public function checkIn(int $reservationId): Stay
    {
        return DB::transaction(function () use ($reservationId) {
            $reservation = $this->reservations->findByIdOrFail($reservationId);

            if ($reservation->status !== ReservationStatus::Confirmed) {
                throw new InvalidStayStateException(
                    'Only confirmed reservations can be checked in.'
                );
            }

            if ($reservation->stay) {
                throw new InvalidStayStateException('This reservation already has an active stay.');
            }

            $stay = $this->stays->create([
                'reservation_id' => $reservation->id,
                'room_id' => $reservation->room_id,
                'guest_id' => $reservation->guest_id,
                'status' => StayStatus::CheckedIn,
                'checked_in_at' => now(),
            ]);

            $this->rooms->updateStatus($reservation->room_id, RoomStatus::Occupied);

            $this->billing->generateInvoiceForStay(
                $this->stays->findByIdOrFail($stay->id)
            );

            return $this->stays->findByIdOrFail($stay->id);
        });
    }

    public function checkOut(int $stayId): Stay
    {
        return DB::transaction(function () use ($stayId) {
            $stay = $this->stays->findByIdOrFail($stayId);

            if ($stay->status !== StayStatus::CheckedIn) {
                throw new InvalidStayStateException(
                    'Only checked-in stays can be checked out.'
                );
            }

            $stay = $this->stays->update($stayId, [
                'status' => StayStatus::CheckedOut,
                'checked_out_at' => now(),
            ]);

            $reservation = $stay->reservation;
            $reservation->update(['status' => ReservationStatus::Completed]);

            $this->rooms->updateStatus($stay->room_id, RoomStatus::Available);

            if ($stay->invoice && $stay->invoice->status->value === 'draft') {
                $this->billing->issueInvoice($stay->invoice->id);
            }

            return $this->stays->findByIdOrFail($stayId);
        });
    }
}
