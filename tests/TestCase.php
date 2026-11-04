<?php

namespace Tests;

use App\Models\User;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Core\Enums\UserRole;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function useAssociation(Association $association): void
    {
        app(AssociationContext::class)->set($association);
    }

    protected function clearAssociationContext(): void
    {
        app(AssociationContext::class)->clear();
    }

    protected function makeAssociation(array $attributes = []): Association
    {
        return Association::query()->create(array_merge([
            'name' => 'Test Association',
            'short_name' => 'TA',
            'slug' => 'test-association-'.Str::lower(Str::random(6)),
            'is_active' => true,
        ], $attributes));
    }

    protected function makeUser(
        Association $association,
        UserRole $role = UserRole::AssociationAdmin,
        array $attributes = []
    ): User {
        return User::query()->create(array_merge([
            'name' => 'Test User',
            'email' => Str::lower(Str::random(10)).'@example.test',
            'password' => 'password',
            'role' => $role->value,
            'association_id' => $association->getKey(),
            'is_active' => true,
        ], $attributes));
    }
}
