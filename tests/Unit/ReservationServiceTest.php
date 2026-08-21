<?php

namespace Tests\Unit;

use App\Modules\Reservation\Repositories\Contracts\ReservationRepositoryInterface;
use App\Modules\Reservation\Services\ReservationService;
use App\Modules\Room\Repositories\Contracts\RoomRepositoryInterface;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    #[Test]
    public function it_calculates_minimum_one_night(): void
    {
        $service = new ReservationService(
            $this->createMock(ReservationRepositoryInterface::class),
            $this->createMock(RoomRepositoryInterface::class),
        );

        $nights = $service->calculateNights(
            Carbon::parse('2026-05-18'),
            Carbon::parse('2026-05-19'),
        );

        $this->assertEquals(1, $nights);
    }

    #[Test]
    public function it_calculates_multi_night_stays(): void
    {
        $service = new ReservationService(
            $this->createMock(ReservationRepositoryInterface::class),
            $this->createMock(RoomRepositoryInterface::class),
        );

        $nights = $service->calculateNights(
            Carbon::parse('2026-05-18'),
            Carbon::parse('2026-05-22'),
        );

        $this->assertEquals(4, $nights);
    }
}
