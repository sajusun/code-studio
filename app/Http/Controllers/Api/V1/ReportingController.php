<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportingController extends Controller
{
    protected $service;

    public function __construct(ReportingService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/v1/reports/occupancy
     * Query params: start_date, end_date (Y-m-d)
     */
    public function occupancyRate(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $data = $this->service->calculateOccupancyRate(
            $request->input('start_date'),
            $request->input('end_date')
        );

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/v1/reports/revenue
     * Returns revenue per day for the given range.
     */
    public function revenuePerDay(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $data = $this->service->calculateRevenuePerDay(
            $request->input('start_date'),
            $request->input('end_date')
        );

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/v1/reports/average-stay
     * Returns average stay length (in nights) for the given range.
     */
    public function averageStayLength(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $data = $this->service->calculateAverageStayLength(
            $request->input('start_date'),
            $request->input('end_date')
        );

        return response()->json(['data' => $data]);
    }
}
?>
