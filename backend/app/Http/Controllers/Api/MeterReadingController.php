<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterReadingRequest;
use App\Models\MeterReading;
use Illuminate\Http\JsonResponse;

class MeterReadingController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            MeterReading::with('customer', 'officer.role')->latest('reading_month')->paginate(20)
        );
    }

    public function monthlyIndex(): JsonResponse
    {
        $month = request('month', now()->format('Y-m'));

        $items = MeterReading::with('customer')
            ->where('reading_month', $month . '-01')
            ->get();

        return response()->json($items);
    }

    public function store(StoreMeterReadingRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['usage_m3'] = $payload['current_value'] - $payload['previous_value'];
        $payload['recorded_by'] = $request->user()->id;
        $payload['reading_month'] = $payload['reading_month'] . '-01';

        $reading = MeterReading::create($payload);

        return response()->json($reading->load('customer', 'officer'), 201);
    }
}
