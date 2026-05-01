<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $district = District::updateOrCreate(['code' => 'KEC001'], ['name' => 'Kecamatan Sukamaju']);
        $districtTwo = District::updateOrCreate(['code' => 'KEC002'], ['name' => 'Kecamatan Mekarjaya']);

        $village = Village::updateOrCreate(
            ['code' => 'DES001'],
            ['district_id' => $district->id, 'name' => 'Desa Sumber Jaya']
        );
        Village::updateOrCreate(
            ['code' => 'DES002'],
            ['district_id' => $district->id, 'name' => 'Desa Harapan Baru']
        );
        Village::updateOrCreate(
            ['code' => 'DES003'],
            ['district_id' => $districtTwo->id, 'name' => 'Desa Mekarsari']
        );

        $users = [
            [
                'name' => 'Root Administrator',
                'email' => 'admin@airbersih.test',
                'role' => 'administrator',
                'phone' => '081200000001',
                'service_area' => 'Semua kecamatan dan desa',
                'district_id' => null,
                'village_id' => null,
            ],
            [
                'name' => 'Operator Kecamatan',
                'email' => 'kecamatan@airbersih.test',
                'role' => 'kecamatan',
                'phone' => '081200000002',
                'service_area' => 'Kecamatan Sukamaju',
                'district_id' => $district->id,
                'village_id' => null,
            ],
            [
                'name' => 'Operator Desa',
                'email' => 'desa@airbersih.test',
                'role' => 'desa',
                'phone' => '081200000003',
                'service_area' => 'Desa Sumber Jaya',
                'district_id' => $district->id,
                'village_id' => $village->id,
            ],
            [
                'name' => 'Petugas Lapangan',
                'email' => 'petugas@airbersih.test',
                'role' => 'petugas_lapangan',
                'phone' => '081200000004',
                'service_area' => 'RT 001 sampai RT 004 Desa Sumber Jaya',
                'district_id' => $district->id,
                'village_id' => $village->id,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'phone' => $user['phone'],
                    'service_area' => $user['service_area'],
                    'role_id' => Role::where('name', $user['role'])->value('id'),
                    'district_id' => $user['district_id'],
                    'village_id' => $user['village_id'],
                ]
            );
        }
    }
}
