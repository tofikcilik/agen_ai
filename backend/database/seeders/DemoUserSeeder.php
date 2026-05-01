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
        $district = District::updateOrCreate(['code' => 'KEC-001'], ['name' => 'Kecamatan Sukamaju']);
        $districtTwo = District::updateOrCreate(['code' => 'KEC-002'], ['name' => 'Kecamatan Mekarjaya']);

        $village = Village::updateOrCreate(
            ['code' => 'DES-001'],
            ['district_id' => $district->id, 'name' => 'Desa Sumber Jaya']
        );
        Village::updateOrCreate(
            ['code' => 'DES-002'],
            ['district_id' => $district->id, 'name' => 'Desa Harapan Baru']
        );
        Village::updateOrCreate(
            ['code' => 'DES-003'],
            ['district_id' => $districtTwo->id, 'name' => 'Desa Mekarsari']
        );

        $users = [
            [
                'name' => 'Root Administrator',
                'email' => 'admin@airbersih.test',
                'role' => 'administrator',
                'district_id' => null,
                'village_id' => null,
            ],
            [
                'name' => 'Operator Kecamatan',
                'email' => 'kecamatan@airbersih.test',
                'role' => 'kecamatan',
                'district_id' => $district->id,
                'village_id' => null,
            ],
            [
                'name' => 'Operator Desa',
                'email' => 'desa@airbersih.test',
                'role' => 'desa',
                'district_id' => $district->id,
                'village_id' => $village->id,
            ],
            [
                'name' => 'Petugas Lapangan',
                'email' => 'petugas@airbersih.test',
                'role' => 'petugas_lapangan',
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
                    'role_id' => Role::where('name', $user['role'])->value('id'),
                    'district_id' => $user['district_id'],
                    'village_id' => $user['village_id'],
                ]
            );
        }
    }
}
