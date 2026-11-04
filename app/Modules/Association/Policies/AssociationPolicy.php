<?php

namespace App\Modules\Association\Policies;

use App\Models\User;
use App\Modules\Association\Models\Association;

class AssociationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function view(User $user, Association $association): bool
    {
        return $user->is_active
            && ($user->isSuperAdmin() || (int) $user->association_id === (int) $association->getKey());
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function update(User $user, Association $association): bool
    {
        return $this->view($user, $association) && $user->canManageAssociation();
    }

    public function delete(User $user, Association $association): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }
}
