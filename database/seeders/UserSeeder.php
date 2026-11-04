<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Enums\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $association = Association::query()->where('slug', 'mpta')->firstOrFail();

        $users = [
            [
                'name' => 'Platform Administrator',
                'email' => 'super@mpta.test',
                'role' => UserRole::SuperAdmin,
            ],
            [
                'name' => 'MPTA Administrator',
                'email' => 'admin@mpta.test',
                'role' => UserRole::AssociationAdmin,
            ],
            [
                'name' => 'MPTA Staff',
                'email' => 'staff@mpta.test',
                'role' => UserRole::Staff,
            ],
        ];

        foreach ($users as $definition) {
            $user = User::query()->firstOrCreate(
                ['email' => $definition['email']],
                [
                    'name' => $definition['name'],
                    'password' => 'ChangeMe@123',
                    'role' => $definition['role']->value,
                    'association_id' => $association->getKey(),
                    'is_active' => true,
                ]
            );

            $user->fill([
                'name' => $definition['name'],
                'association_id' => $association->getKey(),
                'role' => $definition['role']->value,
                'is_active' => true,
            ])->save();
        }

        $this->command?->warn('Seeded accounts use password "ChangeMe@123". Change it before any non-local deployment.');
    }
}
