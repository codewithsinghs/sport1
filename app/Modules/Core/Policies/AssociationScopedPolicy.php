<?php

namespace App\Modules\Core\Policies;

use App\Models\User;
use App\Modules\Core\Association\AssociationContext;
use Illuminate\Database\Eloquent\Model;

abstract class AssociationScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasAssociationContext($user);
    }

    public function view(User $user, Model $model): bool
    {
        return $this->ownsRecord($user, $model);
    }

    public function create(User $user): bool
    {
        return $this->hasAssociationContext($user) && $user->canManageAssociation();
    }

    public function update(User $user, Model $model): bool
    {
        return $this->ownsRecord($user, $model) && $user->canManageAssociation();
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->update($user, $model);
    }

    protected function hasAssociationContext(User $user): bool
    {
        $context = app(AssociationContext::class);

        return $user->is_active
            && $user->association_id !== null
            && $context->has()
            && (int) $user->association_id === (int) $context->id();
    }

    protected function ownsRecord(User $user, Model $model): bool
    {
        return $this->hasAssociationContext($user)
            && (int) $model->getAttribute('association_id') === (int) $user->association_id;
    }
}
