<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        return response()->json(
            $this->scopePayments(
                Payment::query()->with('bill.customer.village', 'officer.role')->latest('payment_date'),
                $user
            )->paginate($this->perPage())
        );
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role');
        $bill = Bill::with('customer')->findOrFail($request->integer('bill_id'));
        $this->ensureBillAccess($user, $bill);

        $payment = Payment::create([
            ...$request->validated(),
            'received_by' => $user->id,
        ]);

        $totalPaid = $bill->payments()->sum('amount_paid');
        $bill->update([
            'status' => $totalPaid >= $bill->amount ? 'paid' : 'partial',
        ]);

        return response()->json($payment->load('bill.customer.village', 'officer.role'), 201);
    }
}
