<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
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
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');
        $customers = $this->scopeCustomers(
            Customer::query()->with('village.district')->latest(),
            $user
        )->paginate($this->perPage());

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $data = $request->validated();
        $village = Village::findOrFail($data['village_id']);

        $this->ensureVillageAccess($user, $village->id);

        $customer = DB::transaction(function () use ($data, $village): Customer {
            $nextSequence = ((int) Customer::where('village_id', $village->id)->lockForUpdate()->max('customer_sequence')) + 1;

            return Customer::create([
                ...Arr::except($data, ['customer_number', 'customer_sequence']),
                'customer_sequence' => $nextSequence,
                'customer_number' => sprintf('%s_%06d', $village->code, $nextSequence),
                'status' => $data['status'] ?? 'active',
                'tariff_per_m3' => $data['tariff_per_m3'] ?? 3500,
            ]);
        });

        return response()->json($customer->load('village.district'), 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        $this->ensureCustomerAccess(request()->user()->loadMissing('role'), $customer);

        return response()->json($customer->load('village.district', 'meterReadings', 'bills', 'complaints'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $data = $request->validated();

        $this->ensureCustomerAccess($user, $customer);
        $this->ensureVillageAccess($user, (int) $data['village_id']);

        $customer->update(Arr::except($data, ['customer_number', 'customer_sequence']));

        return response()->json($customer->fresh('village.district'));
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->ensureCustomerAccess(request()->user()->loadMissing('role'), $customer);

        $customer->delete();

        return response()->json(['message' => 'Pelanggan dihapus.']);
    }
}
