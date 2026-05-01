<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'administrator', 'label' => 'Root Administrator'],
            ['name' => 'kecamatan', 'label' => 'Operator Kecamatan'],
            ['name' => 'desa', 'label' => 'Operator Desa'],
            ['name' => 'petugas_lapangan', 'label' => 'Petugas Lapangan'],
        ])->each(fn (array $role) => Role::updateOrCreate(['name' => $role['name']], $role));
    }
}
