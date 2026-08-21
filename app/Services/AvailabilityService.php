<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Stay;

class AvailabilityService
{
    /**
     * Calculate free room IDs for each date between $start and $end (inclusive).
     *
     * @param string $startDate Y-m-d format
     * @param string $endDate   Y-m-d format
     * @return array            [date => [roomId, ...]]
     */
    public function calculateAvailability(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);
        $result = [];

        // Pre‑load total room IDs to avoid a query per day when all are free
        $allRoomIds = Room::pluck('id')->toArray();
        $totalRooms = count($allRoomIds);
        if ($totalRooms === 0) {
            return [];
        }

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // Rooms that have a stay intersecting the current date
            $occupiedRoomIds = Stay::whereDate('check_in', '<=', $date->toDateString())
                ->whereDate('check_out', '>', $date->toDateString())
                ->pluck('room_id')
                ->unique()
                ->toArray();

            // Available rooms are those not occupied on this day
            $available = array_values(array_diff($allRoomIds, $occupiedRoomIds));
            $result[$date->toDateString()] = $available;
        }

        return $result;
    }
}
