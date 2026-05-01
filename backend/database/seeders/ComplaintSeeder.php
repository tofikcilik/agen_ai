<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $villageOperator = User::where('email', 'desa@airbersih.test')->firstOrFail();
        $fieldOfficer = User::where('email', 'petugas@airbersih.test')->firstOrFail();

        $complaints = [
            [
                'customer_number' => 'PLG-0001',
                'category' => 'air_mati',
                'title' => 'Distribusi berhenti sejak pagi',
                'description' => 'Air tidak mengalir sejak pukul 05.00.',
                'location_details' => 'Blok timur dekat masjid',
                'status' => 'diproses',
                'reported_by' => $villageOperator->id,
                'handled_by' => $fieldOfficer->id,
                'handled_at' => now(),
            ],
            [
                'customer_number' => 'PLG-0003',
                'category' => 'kebocoran',
                'title' => 'Pipa dekat rumah bocor',
                'description' => 'Ada rembesan pada sambungan samping rumah.',
                'location_details' => 'RT 03 RW 01 samping pos ronda',
                'status' => 'baru',
                'reported_by' => $villageOperator->id,
                'handled_by' => null,
                'handled_at' => null,
            ],
        ];

        foreach ($complaints as $item) {
            $customer = Customer::where('customer_number', $item['customer_number'])->firstOrFail();

            Complaint::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'title' => $item['title'],
                ],
                [
                    'reported_by' => $item['reported_by'],
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'location_details' => $item['location_details'],
                    'status' => $item['status'],
                    'handled_by' => $item['handled_by'],
                    'handled_at' => $item['handled_at'],
                ]
            );
        }
    }
}
