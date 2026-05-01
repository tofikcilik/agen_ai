<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Village;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $village = Village::where('code', 'DES001')->firstOrFail();

        $customers = [
            [
                'customer_sequence' => 1,
                'name' => 'Siti Aminah',
                'rt' => '001',
                'rw' => '001',
                'address_detail' => 'Dusun 1, Desa Sumber Jaya',
                'phone' => '081234567801',
                'latitude' => -6.2146200,
                'longitude' => 106.8451300,
                'meter_number' => 'MTR-1001',
                'status' => 'active',
                'tariff_per_m3' => 3500,
            ],
            [
                'customer_sequence' => 2,
                'name' => 'Rahmat Hidayat',
                'rt' => '002',
                'rw' => '001',
                'address_detail' => 'Dusun 2, Desa Sumber Jaya',
                'phone' => '081234567802',
                'latitude' => -6.2151000,
                'longitude' => 106.8462000,
                'meter_number' => 'MTR-1002',
                'status' => 'active',
                'tariff_per_m3' => 3500,
            ],
            [
                'customer_sequence' => 3,
                'name' => 'Dewi Lestari',
                'rt' => '003',
                'rw' => '001',
                'address_detail' => 'Dusun 3, Desa Sumber Jaya',
                'phone' => '081234567803',
                'latitude' => -6.2178000,
                'longitude' => 106.8429000,
                'meter_number' => 'MTR-1003',
                'status' => 'inactive',
                'tariff_per_m3' => 3000,
            ],
        ];

        foreach ($customers as $customer) {
            $customerNumber = sprintf('%s_%06d', $village->code, $customer['customer_sequence']);

            Customer::updateOrCreate(
                ['customer_number' => $customerNumber],
                [
                    'village_id' => $village->id,
                    'customer_number' => $customerNumber,
                    ...$customer,
                ]
            );
        }
    }
}
