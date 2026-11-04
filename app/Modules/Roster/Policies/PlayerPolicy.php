<?php

namespace App\Modules\Roster\Policies;

use App\Models\User;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Roster\Models\Player;

/**
 * The registry is global, so access is governed by links rather than an
 * association_id column. An association may only see players it has claimed,
 * players it owns, or players a records officer has published.
 */
class PlayerPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasContext($user);
    }

    public function view(User $user, Player $player): bool
    {
        return $this->hasContext($user) && $this->isVisible($user, $player);
    }

    public function create(User $user): bool
    {
        return $this->hasContext($user) && $user->canManageAssociation();
    }

    public function update(User $user, Player $player): bool
    {
        if (! $this->hasContext($user) || ! $user->canManageAssociation()) {
            return false;
        }

        return $user->isSuperAdmin() || (int) $player->owner_association_id === (int) $user->association_id;
    }

    public function delete(User $user, Player $player): bool
    {
        return $user->isSuperAdmin() && $this->view($user, $player);
    }

    /**
     * Reusing an existing record by linking it to the current association.
     */
    public function link(User $user, Player $player): bool
    {
        return $this->hasContext($user)
            && $user->canManageAssociation()
            && $this->isInRegistry($user, $player);
    }

    public function unlink(User $user, Player $player): bool
    {
        return $this->hasContext($user)
            && $user->canManageAssociation()
            && $this->isVisible($user, $player);
    }

    private function hasContext(User $user): bool
    {
        $context = app(AssociationContext::class);

        return $user->is_active
            && $user->association_id !== null
            && $context->has()
            && (int) $user->association_id === (int) $context->id();
    }

    private function isVisible(User $user, Player $player): bool
    {
        return $this->isClaimed($user, $player) || $this->isOwned($user, $player);
    }

    /**
     * Published profiles are searchable so records can be reused instead of duplicated.
     */
    private function isInRegistry(User $user, Player $player): bool
    {
        return $player->is_public || $this->isVisible($user, $player);
    }

    private function isOwned(User $user, Player $player): bool
    {
        return (int) $player->owner_association_id === (int) $user->association_id;
    }

    private function isClaimed(User $user, Player $player): bool
    {
        $associationId = (int) $user->association_id;

        return $player->associations
            ->contains(fn (Association $association): bool => (int) $association->id === $associationId);
    }
}
