<?php

namespace App\Modules\Engagement\Policies;

use App\Models\User;
use App\Modules\Core\Policies\AssociationScopedPolicy;
use App\Modules\Engagement\Models\ContactLead;

class ContactLeadPolicy extends AssociationScopedPolicy
{
    public function create(User $user): bool
    {
        return false;
    }

    public function delete(User $user, ContactLead $model): bool
    {
        return $this->ownsRecord($user, $model) && $user->isSuperAdmin();
    }
}
