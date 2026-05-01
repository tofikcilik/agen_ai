<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

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
        $this->ensureVillageAccess($user, (int) $request->integer('village_id'));

        $customer = Customer::create($request->validated());

        return response()->json($customer->load('village.district'), 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        $this->ensureCustomerAccess(request()->user()->loadMissing('role'), $customer);

        return response()->json($customer->load('village.district', 'meterReadings', 'bills'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $this->ensureCustomerAccess($user, $customer);
        $this->ensureVillageAccess($user, (int) $request->integer('village_id'));

        $customer->update($request->validated());

        return response()->json($customer->fresh('village.district'));
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->ensureCustomerAccess(request()->user()->loadMissing('role'), $customer);

        $customer->delete();

        return response()->json(['message' => 'Pelanggan dihapus.']);
    }
}
