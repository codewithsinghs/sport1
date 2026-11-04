<?php

namespace App\Modules\Sport\Policies;

use App\Models\User;
use App\Modules\Sport\Models\Sport;

class SportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->association_id !== null;
    }

    public function view(User $user, Sport $sport): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function update(User $user, Sport $sport): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function delete(User $user, Sport $sport): bool
    {
        return $this->update($user, $sport);
    }

    public function toggleForAssociation(User $user): bool
    {
        return $user->is_active
            && $user->association_id !== null
            && $user->canManageAssociation();
    }
}
