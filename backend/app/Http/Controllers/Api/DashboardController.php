<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\ScopesDataByRole;
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
    use ScopesDataByRole;

    public function index(): JsonResponse
    {
        $user = request()->user()->loadMissing('role');

        $customers = $this->scopeCustomers(Customer::query(), $user);
        $meterReadings = $this->scopeMeterReadings(MeterReading::query(), $user);
        $bills = $this->scopeBills(Bill::query(), $user);
        $payments = $this->scopePayments(Payment::query(), $user);
        $complaints = $this->scopeComplaints(Complaint::query(), $user);

        return response()->json([
            'summary' => [
                'customers' => (clone $customers)->count(),
                'monthly_usage_m3' => (clone $meterReadings)->whereMonth('reading_month', now()->month)->sum('usage_m3'),
                'arrears_amount' => (clone $bills)->whereIn('status', ['unpaid', 'partial'])->sum('amount'),
                'payments_this_month' => (clone $payments)->whereMonth('payment_date', now()->month)->sum('amount_paid'),
                'open_complaints' => (clone $complaints)->whereIn('status', ['baru', 'diproses'])->count(),
            ],
            'usage_trend' => $this->scopeMeterReadings(MeterReading::query(), $user)
                ->selectRaw('DATE_FORMAT(reading_month, "%Y-%m") as month, SUM(usage_m3) as total_usage')
                ->groupBy('month')
                ->orderBy('month')
                ->limit(6)
                ->get(),
            'payment_status' => $this->scopeBills(Bill::query(), $user)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get(),
        ]);
    }
}
