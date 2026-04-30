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
        $village = Village::updateOrCreate(
            ['code' => 'DES-001'],
            ['district_id' => $district->id, 'name' => 'Desa Sumber Jaya']
        );

        $users = [
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
