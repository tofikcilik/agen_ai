<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DemoUserSeeder::class,
            CustomerSeeder::class,
            MeterReadingSeeder::class,
            BillSeeder::class,
            PaymentSeeder::class,
            ComplaintSeeder::class,
        ]);
    }
}
