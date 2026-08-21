<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReservation extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
        $this->reservation->loadMissing(['room', 'guest']);
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $roomNumber = $this->reservation->room?->number ?? 'N/A';
        $guestName = $this->reservation->guest?->full_name ?? 'N/A';
        $checkIn = $this->reservation->check_in_date?->format('Y-m-d') ?? 'N/A';
        $checkOut = $this->reservation->check_out_date?->format('Y-m-d') ?? 'N/A';

        return [
            'type' => 'reservation',
            'target_id' => $this->reservation->id,
            'title' => 'New Booking',
            'message' => "Room {$roomNumber} booked by {$guestName} for {$checkIn} to {$checkOut}",
        ];
    }
}
