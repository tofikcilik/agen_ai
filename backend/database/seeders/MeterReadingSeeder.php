<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\MeterReading;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeterReadingSeeder extends Seeder
{
    public function run(): void
    {
        $officer = User::where('email', 'petugas@airbersih.test')->firstOrFail();

        $readings = [
            [
                'customer_number' => 'PLG-0001',
                'reading_month' => '2026-05-01',
                'previous_value' => 120,
                'current_value' => 148,
                'usage_m3' => 28,
                'notes' => 'Meter normal',
            ],
            [
                'customer_number' => 'PLG-0002',
                'reading_month' => '2026-05-01',
                'previous_value' => 90,
                'current_value' => 112,
                'usage_m3' => 22,
                'notes' => 'Pencatatan rutin',
            ],
        ];

        foreach ($readings as $reading) {
            $customer = Customer::where('customer_number', $reading['customer_number'])->firstOrFail();

            MeterReading::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'reading_month' => $reading['reading_month'],
                ],
                [
                    'recorded_by' => $officer->id,
                    'previous_value' => $reading['previous_value'],
                    'current_value' => $reading['current_value'],
                    'usage_m3' => $reading['usage_m3'],
                    'notes' => $reading['notes'],
                ]
            );
        }
    }
}
