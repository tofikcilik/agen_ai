<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\MeterReading;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        $bills = [
            [
                'customer_number' => 'DES001_000001',
                'billing_month' => '2026-05-01',
                'due_date' => '2026-05-25',
                'status' => 'partial',
            ],
            [
                'customer_number' => 'DES001_000002',
                'billing_month' => '2026-05-01',
                'due_date' => '2026-05-25',
                'status' => 'paid',
            ],
        ];

        foreach ($bills as $item) {
            $customer = Customer::where('customer_number', $item['customer_number'])->firstOrFail();
            $reading = MeterReading::where('customer_id', $customer->id)
                ->where('reading_month', $item['billing_month'])
                ->firstOrFail();

            Bill::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'billing_month' => $item['billing_month'],
                ],
                [
                    'meter_reading_id' => $reading->id,
                    'usage_m3' => $reading->usage_m3,
                    'amount' => $reading->usage_m3 * $customer->tariff_per_m3,
                    'status' => $item['status'],
                    'due_date' => $item['due_date'],
                ]
            );
        }
    }
}
