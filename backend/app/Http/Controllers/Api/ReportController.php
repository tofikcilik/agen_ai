<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\MeterReading;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financialSummary(): JsonResponse
    {
        $data = Payment::query()
            ->join('bills', 'payments.bill_id', '=', 'bills.id')
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->join('villages', 'customers.village_id', '=', 'villages.id')
            ->selectRaw('villages.name as village_name, SUM(payments.amount_paid) as total_payment, COUNT(payments.id) as transaction_count')
            ->groupBy('villages.name')
            ->orderByDesc('total_payment')
            ->get();

        return response()->json($data);
    }

    public function arrears(): JsonResponse
    {
        $data = Bill::with('customer.village')
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('due_date')
            ->get();

        return response()->json($data);
    }

    public function usage(): JsonResponse
    {
        $data = MeterReading::query()
            ->join('customers', 'meter_readings.customer_id', '=', 'customers.id')
            ->join('villages', 'customers.village_id', '=', 'villages.id')
            ->selectRaw('DATE_FORMAT(reading_month, "%Y-%m") as month, villages.name as village_name, SUM(usage_m3) as total_usage')
            ->groupBy('month', 'villages.name')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }
}
