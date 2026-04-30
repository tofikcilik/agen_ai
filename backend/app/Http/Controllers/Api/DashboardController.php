<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\MeterReading;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'summary' => [
                'customers' => Customer::count(),
                'monthly_usage_m3' => MeterReading::whereMonth('reading_month', now()->month)->sum('usage_m3'),
                'arrears_amount' => Bill::whereIn('status', ['unpaid', 'partial'])->sum('amount'),
                'payments_this_month' => Payment::whereMonth('payment_date', now()->month)->sum('amount_paid'),
                'open_complaints' => Complaint::whereIn('status', ['baru', 'diproses'])->count(),
            ],
            'usage_trend' => MeterReading::selectRaw('DATE_FORMAT(reading_month, "%Y-%m") as month, SUM(usage_m3) as total_usage')
                ->groupBy('month')
                ->orderBy('month')
                ->limit(6)
                ->get(),
            'payment_status' => Bill::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get(),
        ]);
    }
}
