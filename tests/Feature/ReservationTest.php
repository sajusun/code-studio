<?php

namespace Tests\Feature;

use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private Room $room;

    private Guest $guest;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());

        $type = RoomType::query()->create([
            'name' => 'Standard',
            'base_rate' => 100.00,
            'capacity' => 2,
        ]);

        $this->room = Room::query()->create([
            'room_type_id' => $type->id,
            'number' => '101',
            'floor' => 1,
            'status' => RoomStatus::Available,
        ]);

        $this->guest = Guest::query()->create([
            'first_name' => 'Test',
            'last_name' => 'Guest',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_search_available_rooms(): void
    {
        $checkIn = Carbon::today()->addDays(1);
        $checkOut = Carbon::today()->addDays(3);

        $response = $this->getJson('/api/v1/availability?'.http_build_query([
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
        ]));

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_reservation(): void
    {
        $checkIn = Carbon::today()->addDays(5)->toDateString();
        $checkOut = Carbon::today()->addDays(8)->toDateString();

        $response = $this->postJson('/api/v1/reservations', [
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'guests_count' => 2,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'confirmed')
            ->assertJsonPath('data.estimated_total', 300);

        $this->assertDatabaseHas('reservations', [
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'status' => ReservationStatus::Confirmed->value,
        ]);

        $this->room->refresh();
        $this->assertEquals(RoomStatus::Reserved, $this->room->status);
    }

    public function test_prevents_overlapping_reservations(): void
    {
        $checkIn = Carbon::today()->addDays(10);
        $checkOut = Carbon::today()->addDays(13);

        Reservation::query()->create([
            'reference' => 'RES-EXISTING1',
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'status' => ReservationStatus::Confirmed,
            'nightly_rate' => 100,
            'estimated_total' => 300,
            'confirmed_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reservations', [
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => $checkIn->copy()->addDay()->toDateString(),
            'check_out_date' => $checkIn->copy()->addDays(2)->toDateString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('error', 'room_not_available');
    }

    public function test_adjacent_bookings_do_not_overlap(): void
    {
        $firstCheckIn = Carbon::today()->addDays(20);
        $firstCheckOut = Carbon::today()->addDays(23);

        Reservation::query()->create([
            'reference' => 'RES-ADJACENT1',
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => $firstCheckIn->toDateString(),
            'check_out_date' => $firstCheckOut->toDateString(),
            'status' => ReservationStatus::Confirmed,
            'nightly_rate' => 100,
            'estimated_total' => 300,
            'confirmed_at' => now(),
        ]);

        $response = $this->postJson('/api/v1/reservations', [
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => $firstCheckOut->toDateString(),
            'check_out_date' => $firstCheckOut->copy()->addDays(3)->toDateString(),
        ]);

        $response->assertCreated();
    }
}
