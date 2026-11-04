<?php

namespace Database\Seeders;

use App\Modules\Sport\Models\Sport;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        Sport::query()->updateOrCreate(
            ['slug' => 'targetball'],
            [
                'name' => 'Targetball',
                'description' => 'Targetball competition programme managed by the association.',
                'is_active' => true,
            ]
        );
    }
}
