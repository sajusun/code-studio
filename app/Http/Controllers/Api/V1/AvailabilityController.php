<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    protected $service;

    public function __construct(AvailabilityService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/v1/availability
     *
     * Query parameters:
     *   - start_date (Y-m-d)
     *   - end_date   (Y-m-d)
     *
     * Returns an array keyed by date with a list of available room IDs.
     */
    public function getFreeSlots(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start = $request->input('start_date');
        $end   = $request->input('end_date');

        $availability = $this->service->calculateAvailability($start, $end);

        return response()->json([
            'data' => $availability,
        ]);
    }
}
?>
