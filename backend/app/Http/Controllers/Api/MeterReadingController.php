<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterReadingRequest;
use App\Models\Customer;
use App\Models\MeterReading;
use Illuminate\Http\JsonResponse;

class MeterReadingController extends Controller
{
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        return response()->json(
            $this->scopeMeterReadings(
                MeterReading::query()->with('customer.village', 'officer.role')->latest('reading_month'),
                $user
            )->paginate($this->perPage())
        );
    }

    public function monthlyIndex(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');
        $month = request('month', now()->format('Y-m'));

        $items = $this->scopeMeterReadings(
            MeterReading::query()->with('customer.village')->where('reading_month', $month . '-01'),
            $user
        )->get();

        return response()->json($items);
    }

    public function store(StoreMeterReadingRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $customer = Customer::findOrFail($request->integer('customer_id'));
        $this->ensureCustomerAccess($user, $customer);

        $payload = $request->validated();
        $payload['usage_m3'] = $payload['current_value'] - $payload['previous_value'];
        $payload['recorded_by'] = $user->id;
        $payload['reading_month'] = $payload['reading_month'] . '-01';

        $reading = MeterReading::create($payload);

        return response()->json($reading->load('customer.village', 'officer.role'), 201);
    }
}
