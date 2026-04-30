<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Payment::with('bill.customer', 'officer')->latest('payment_date')->paginate(20)
        );
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create([
            ...$request->validated(),
            'received_by' => $request->user()->id,
        ]);

        $bill = Bill::findOrFail($request->integer('bill_id'));
        $totalPaid = $bill->payments()->sum('amount_paid');
        $bill->update([
            'status' => $totalPaid >= $bill->amount ? 'paid' : 'partial',
        ]);

        return response()->json($payment->load('bill.customer', 'officer'), 201);
    }
}
