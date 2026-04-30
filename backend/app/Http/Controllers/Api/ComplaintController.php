<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintStatusRequest;
use App\Models\Complaint;
use Illuminate\Http\JsonResponse;

class ComplaintController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Complaint::with('customer')->latest()->paginate(20)
        );
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $complaint = Complaint::create([
            ...$request->validated(),
            'reported_by' => $request->user()?->id,
            'status' => 'baru',
        ]);

        return response()->json($complaint->load('customer'), 201);
    }

    public function updateStatus(UpdateComplaintStatusRequest $request, Complaint $complaint): JsonResponse
    {
        $complaint->update([
            'status' => $request->string('status'),
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
        ]);

        return response()->json($complaint->fresh('customer'));
    }
}
