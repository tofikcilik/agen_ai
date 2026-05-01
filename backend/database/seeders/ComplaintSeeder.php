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
                'customer_number' => 'DES001_000001',
                'reporter_name' => 'Siti Aminah',
                'reporter_phone' => '081234567801',
                'category' => 'air_mati',
                'title' => 'Distribusi berhenti sejak pagi',
                'description' => 'Air tidak mengalir sejak pukul 05.00.',
                'disturbance_location' => 'Blok timur dekat masjid, RT 001/RW 001',
                'latitude' => -6.2146200,
                'longitude' => 106.8451300,
                'status' => 'diproses',
                'reported_by' => $villageOperator->id,
                'handled_by' => $fieldOfficer->id,
                'handled_at' => now(),
            ],
            [
                'customer_number' => 'DES001_000003',
                'reporter_name' => 'Dewi Lestari',
                'reporter_phone' => '081234567803',
                'category' => 'kebocoran',
                'title' => 'Pipa dekat rumah bocor',
                'description' => 'Ada rembesan pada sambungan samping rumah.',
                'disturbance_location' => 'RT 003/RW 001 samping pos ronda',
                'latitude' => -6.2178000,
                'longitude' => 106.8429000,
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
                    'village_id' => $customer->village_id,
                    'reported_by' => $item['reported_by'],
                    'reporter_name' => $item['reporter_name'],
                    'reporter_phone' => $item['reporter_phone'],
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'disturbance_location' => $item['disturbance_location'],
                    'latitude' => $item['latitude'],
                    'longitude' => $item['longitude'],
                    'status' => $item['status'],
                    'handled_by' => $item['handled_by'],
                    'handled_at' => $item['handled_at'],
                ]
            );
        }
    }
}
