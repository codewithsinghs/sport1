<?php

namespace App\Modules\Geography\Policies;

use App\Models\User;
use App\Modules\Geography\Models\District;

class DistrictPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->association_id !== null;
    }

    public function view(User $user, District $district): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function update(User $user, District $district): bool
    {
        return $user->is_active && $user->isSuperAdmin();
    }

    public function delete(User $user, District $district): bool
    {
        return $this->update($user, $district);
    }
}
