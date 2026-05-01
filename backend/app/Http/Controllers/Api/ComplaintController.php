<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintStatusRequest;
use App\Models\Complaint;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class ComplaintController extends Controller
{
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        return response()->json(
            $this->scopeComplaints(
                Complaint::query()->with('customer.village', 'customer.village.district')->latest(),
                $user
            )->paginate($this->perPage())
        );
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $customer = Customer::findOrFail($request->integer('customer_id'));
        $this->ensureCustomerAccess($user, $customer);

        $complaint = Complaint::create([
            ...$request->validated(),
            'reported_by' => $user?->id,
            'status' => 'baru',
        ]);

        return response()->json($complaint->load('customer.village'), 201);
    }

    public function updateStatus(UpdateComplaintStatusRequest $request, Complaint $complaint): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $this->ensureComplaintAccess($user, $complaint->loadMissing('customer'));

        $complaint->update([
            'status' => $request->string('status')->toString(),
            'handled_by' => $user->id,
            'handled_at' => now(),
        ]);

        return response()->json($complaint->fresh('customer.village'));
    }
}
