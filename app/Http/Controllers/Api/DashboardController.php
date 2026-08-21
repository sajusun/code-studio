<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $activeGuests = Guest::count();
        $totalBookings = Reservation::count();

        // Recent bookings (last 5)
        $recentBookings = Reservation::with(['guest', 'room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Room status distribution
        $roomStatuses = Room::select('status', DB::raw('count(*) as value'))
            ->groupBy('status')
            ->get();

        // Mock Revenue Data for Chart (last 7 days)
        $revenueChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $revenueChartData[] = [
                'name' => now()->subDays($i)->format('M d'),
                'revenue' => rand(1000, 5000),
            ];
        }

        return response()->json([
            'stats' => [
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'active_guests' => $activeGuests,
                'total_bookings' => $totalBookings,
                'revenue_today' => $revenueChartData[6]['revenue'],
            ],
            'recent_bookings' => $recentBookings,
            'room_statuses' => $roomStatuses,
            'revenue_chart' => $revenueChartData,
        ]);
    }
}
