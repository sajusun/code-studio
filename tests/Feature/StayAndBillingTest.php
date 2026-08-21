<?php

namespace Tests\Feature;

use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Shared\Enums\ReservationStatus;
use App\Modules\Shared\Enums\RoomStatus;
use App\Models\User;
use App\Modules\Shared\Enums\StayStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StayAndBillingTest extends TestCase
{
    use RefreshDatabase;

    private Reservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());

        $type = RoomType::query()->create([
            'name' => 'Deluxe',
            'base_rate' => 150.00,
            'capacity' => 2,
        ]);

        $room = Room::query()->create([
            'room_type_id' => $type->id,
            'number' => '201',
            'floor' => 2,
            'status' => RoomStatus::Reserved,
        ]);

        $guest = Guest::query()->create([
            'first_name' => 'Billing',
            'last_name' => 'Test',
            'email' => 'billing@example.com',
        ]);

        $this->reservation = Reservation::query()->create([
            'reference' => 'RES-BILLING1',
            'room_id' => $room->id,
            'guest_id' => $guest->id,
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDays(3)->toDateString(),
            'status' => ReservationStatus::Confirmed,
            'nightly_rate' => 150.00,
            'estimated_total' => 450.00,
            'confirmed_at' => now(),
        ]);
    }

    public function test_check_in_creates_stay_and_invoice(): void
    {
        $response = $this->postJson("/api/v1/reservations/{$this->reservation->id}/check-in");

        $response->assertCreated()
            ->assertJsonPath('data.status', 'checked_in');

        $this->assertDatabaseHas('stays', [
            'reservation_id' => $this->reservation->id,
            'status' => StayStatus::CheckedIn->value,
        ]);

        $this->assertDatabaseHas('invoices', [
            'stay_id' => $response->json('data.id'),
            'nights' => 3,
            'room_charges' => 450.00,
        ]);
    }

    public function test_billing_calculates_nights_and_tax(): void
    {
        $checkIn = $this->postJson("/api/v1/reservations/{$this->reservation->id}/check-in");
        $stayId = $checkIn->json('data.id');
        $invoiceId = $checkIn->json('data.invoice.id');

        $this->postJson("/api/v1/invoices/{$invoiceId}/services", [
            'description' => 'Room Service',
            'unit_price' => 25.00,
            'quantity' => 2,
        ])->assertOk();

        $invoice = $this->getJson("/api/v1/invoices/{$invoiceId}");
        $invoice->assertOk()
            ->assertJsonPath('data.room_charges', 450)
            ->assertJsonPath('data.service_charges', 50)
            ->assertJsonPath('data.tax_amount', 50)
            ->assertJsonPath('data.total_amount', 550);

        $this->postJson("/api/v1/stays/{$stayId}/check-out")
            ->assertOk()
            ->assertJsonPath('data.status', 'checked_out');
    }
}
