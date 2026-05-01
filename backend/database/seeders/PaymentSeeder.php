<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $villageOperator = User::where('email', 'desa@airbersih.test')->firstOrFail();
        $fieldOfficer = User::where('email', 'petugas@airbersih.test')->firstOrFail();

        $payments = [
            [
                'customer_number' => 'DES001_000001',
                'payment_date' => '2026-05-11',
                'amount_paid' => 50000,
                'payment_method' => 'transfer',
                'reference_number' => 'TRX-ABM-001',
                'notes' => 'Bayar sebagian',
                'received_by' => $villageOperator->id,
            ],
            [
                'customer_number' => 'DES001_000002',
                'payment_date' => '2026-05-10',
                'amount_paid' => 77000,
                'payment_method' => 'cash',
                'reference_number' => 'TRX-ABM-002',
                'notes' => 'Lunas di tempat',
                'received_by' => $fieldOfficer->id,
            ],
        ];

        foreach ($payments as $item) {
            $bill = Bill::whereHas('customer', function ($query) use ($item): void {
                $query->where('customer_number', $item['customer_number']);
            })->where('billing_month', '2026-05-01')->firstOrFail();

            Payment::updateOrCreate(
                [
                    'bill_id' => $bill->id,
                    'payment_date' => $item['payment_date'],
                    'amount_paid' => $item['amount_paid'],
                ],
                [
                    'received_by' => $item['received_by'],
                    'payment_method' => $item['payment_method'],
                    'reference_number' => $item['reference_number'],
                    'notes' => $item['notes'],
                ]
            );
        }
    }
}
