<?php

namespace App\Modules\Programme\Policies;

use App\Models\User;
use App\Modules\Core\Policies\AssociationScopedPolicy;
use App\Modules\Programme\Models\Event;

class EventPolicy extends AssociationScopedPolicy
{
    public function approve(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    public function publish(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    public function recordRoster(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    public function uploadMedia(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }
}
