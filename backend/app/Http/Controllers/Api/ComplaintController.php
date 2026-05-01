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
                Complaint::query()->with('customer.village.district', 'village.district')->latest(),
                $user
            )->paginate($this->perPage())
        );
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $data = $request->validated();
        $customer = null;

        if (! empty($data['customer_id'])) {
            $customer = Customer::with('village')->findOrFail($data['customer_id']);
            $this->ensureCustomerAccess($user, $customer);
        } elseif (! empty($data['village_id'])) {
            $this->ensureVillageAccess($user, (int) $data['village_id']);
        }

        $complaint = Complaint::create([
            ...$data,
            'village_id' => $data['village_id'] ?? $customer?->village_id,
            'reporter_phone' => $data['reporter_phone'] ?? $customer?->phone,
            'category' => $data['category'] ?? 'lainnya',
            'reported_by' => $user?->id,
            'status' => 'baru',
        ]);

        return response()->json($complaint->load('customer.village.district', 'village.district'), 201);
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

        return response()->json($complaint->fresh('customer.village.district', 'village.district'));
    }
}
