<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateBillRequest;
use App\Models\Bill;
use App\Models\MeterReading;
use Illuminate\Http\JsonResponse;

class BillController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Bill::with('customer.village', 'payments')->latest('billing_month')->paginate(20)
        );
    }

    public function show(Bill $bill): JsonResponse
    {
        return response()->json($bill->load('customer.village.district', 'meterReading', 'payments'));
    }

    public function generate(GenerateBillRequest $request): JsonResponse
    {
        $month = $request->string('reading_month')->toString() . '-01';
        $dueDate = $request->date('due_date');

        $generated = [];

        MeterReading::with('customer')
            ->where('reading_month', $month)
            ->chunk(100, function ($readings) use (&$generated, $dueDate, $month): void {
                foreach ($readings as $reading) {
                    $generated[] = Bill::updateOrCreate(
                        [
                            'customer_id' => $reading->customer_id,
                            'billing_month' => $month,
                        ],
                        [
                            'meter_reading_id' => $reading->id,
                            'usage_m3' => $reading->usage_m3,
                            'amount' => $reading->usage_m3 * $reading->customer->tariff_per_m3,
                            'status' => 'unpaid',
                            'due_date' => $dueDate,
                        ]
                    );
                }
            });

        return response()->json([
            'message' => 'Tagihan berhasil digenerate.',
            'count' => count($generated),
            'data' => $generated,
        ]);
    }
}
