<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([
                GeographySeeder::class,
                SportSeeder::class,
                AssociationSeeder::class,
                AssociationConfigSeeder::class,
                UserSeeder::class,
            ]);
        });
    }
}
