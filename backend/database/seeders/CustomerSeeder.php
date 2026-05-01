<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Village;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $village = Village::where('code', 'DES-001')->firstOrFail();

        $customers = [
            [
                'customer_number' => 'PLG-0001',
                'name' => 'Siti Aminah',
                'address_rt_rw' => 'RT 01 / RW 01',
                'address_detail' => 'Dusun 1, Desa Sumber Jaya',
                'phone' => '081234567801',
                'meter_number' => 'MTR-1001',
                'status' => 'active',
                'tariff_per_m3' => 3500,
            ],
            [
                'customer_number' => 'PLG-0002',
                'name' => 'Rahmat Hidayat',
                'address_rt_rw' => 'RT 02 / RW 01',
                'address_detail' => 'Dusun 2, Desa Sumber Jaya',
                'phone' => '081234567802',
                'meter_number' => 'MTR-1002',
                'status' => 'active',
                'tariff_per_m3' => 3500,
            ],
            [
                'customer_number' => 'PLG-0003',
                'name' => 'Dewi Lestari',
                'address_rt_rw' => 'RT 03 / RW 01',
                'address_detail' => 'Dusun 3, Desa Sumber Jaya',
                'phone' => '081234567803',
                'meter_number' => 'MTR-1003',
                'status' => 'inactive',
                'tariff_per_m3' => 3000,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['customer_number' => $customer['customer_number']],
                ['village_id' => $village->id, ...$customer]
            );
        }
    }
}
