<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Substitute;
use App\Transformers\Api\SubstituteTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubstituteController extends Controller
{
    /**
     * Widest window a single request may ask for, in days.
     */
    private const MAX_RANGE_DAYS = 366;

    /**
     * List substitutes falling within an inclusive date range.
     */
    public function index(Request $request): JsonResponse
    {
        $req = $request->validate([
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'unassigned' => ['nullable', 'boolean'],
        ]);

        $from = Carbon::parse($req['from'])->startOfDay();
        $to = Carbon::parse($req['to'])->startOfDay();

        if ($from->diffInDays($to) + 1 > self::MAX_RANGE_DAYS) {
            return response()->json([
                'message' => 'The date range may not span more than '.self::MAX_RANGE_DAYS.' days.',
            ], 422);
        }

        $substitutes = Substitute::query()
            ->whereBetween('date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->when(isset($req['school_id']), fn ($query) => $query->where('school_id', $req['school_id']))
            ->when(
                filter_var($req['unassigned'] ?? false, FILTER_VALIDATE_BOOLEAN),
                fn ($query) => $query->whereNull('volunteer')
            )
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'data' => fractal($substitutes, new SubstituteTransformer())->toArray()['data'],
            'meta' => [
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
                'school_id' => isset($req['school_id']) ? (int) $req['school_id'] : null,
                'total' => $substitutes->count(),
            ],
        ]);
    }
}
