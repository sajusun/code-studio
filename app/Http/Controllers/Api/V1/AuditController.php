<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
{
    /**
     * Return paginated audit logs.
     *
     * Supports optional query parameters: page, per_page, search, from, to.
     */
    public function index(Request $request)
    {
        try {
            $query = Activity::query();

            // Search by description or causer name
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhereHas('causer', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            // Date range filter
            if ($from = $request->input('from')) {
                $query->where('created_at', '>=', $from);
            }
            if ($to = $request->input('to')) {
                $query->where('created_at', '<=', $to);
            }

            $perPage = $request->input('per_page', 25);

            $logs = $query->with(['causer'])
                ->orderByDesc('created_at')
                ->paginate($perPage);

            return response()->json($logs);
        } catch (\Exception $e) {
            Log::error('AuditController error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to retrieve audit logs'], 500);
        }
    }
}
