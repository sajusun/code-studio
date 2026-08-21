<?php

namespace Tests\Unit;

use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Repositories\ReservationRepository;
use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_adjacent_dates_do_not_overlap(): void
    {
        $type = RoomType::query()->create([
            'name' => 'Standard',
            'base_rate' => 100,
            'capacity' => 2,
        ]);

        $room = Room::query()->create([
            'room_type_id' => $type->id,
            'number' => '101',
            'floor' => 1,
            'status' => RoomStatus::Available,
        ]);

        $guest = Guest::query()->create([
            'first_name' => 'A',
            'last_name' => 'B',
            'email' => 'ab@example.com',
        ]);

        Reservation::query()->create([
            'reference' => 'RES-1',
            'room_id' => $room->id,
            'guest_id' => $guest->id,
            'check_in_date' => '2026-06-07',
            'check_out_date' => '2026-06-10',
            'status' => ReservationStatus::Confirmed,
            'nightly_rate' => 100,
            'estimated_total' => 300,
        ]);

        $repository = new ReservationRepository;

        $this->assertFalse($repository->hasOverlappingBooking(
            $room->id,
            Carbon::parse('2026-06-10'),
            Carbon::parse('2026-06-13'),
        ));
    }
}
