<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::with('village.district')->latest()->paginate(20);

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $village = Village::findOrFail($data['village_id']);

        $customer = DB::transaction(function () use ($data, $village): Customer {
            $nextSequence = ((int) Customer::where('village_id', $village->id)->lockForUpdate()->max('customer_sequence')) + 1;
            $customerNumber = sprintf('%s_%06d', $village->code, $nextSequence);

            return Customer::create([
                ...Arr::except($data, ['customer_number', 'customer_sequence']),
                'customer_sequence' => $nextSequence,
                'customer_number' => $customerNumber,
                'status' => $data['status'] ?? 'active',
                'tariff_per_m3' => $data['tariff_per_m3'] ?? 3500,
            ]);
        });

        return response()->json($customer->load('village.district'), 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer->load('village.district', 'meterReadings', 'bills', 'complaints'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $data = Arr::except($request->validated(), ['customer_number', 'customer_sequence']);

        $customer->update($data);

        return response()->json($customer->fresh('village.district'));
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(['message' => 'Pelanggan dihapus.']);
    }
}
