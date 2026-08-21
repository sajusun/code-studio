<?php

namespace Tests\Unit;

use App\Modules\Billing\Services\BillingService;
use App\Modules\Reservation\Services\ReservationService;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BillingServiceTest extends TestCase
{
    #[Test]
    public function it_calculates_nights_correctly(): void
    {
        $reservationService = $this->createMock(ReservationService::class);
        $reservationService->method('calculateNights')->willReturn(3);

        $billing = new BillingService(
            $this->createMock(\App\Modules\Billing\Repositories\Contracts\InvoiceRepositoryInterface::class),
            $reservationService,
        );

        $nights = $billing->calculateNights(
            Carbon::parse('2026-05-18'),
            Carbon::parse('2026-05-21'),
        );

        $this->assertEquals(3, $nights);
    }
}
