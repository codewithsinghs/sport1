<?php

namespace App\Modules\Core\Association;

use App\Modules\Association\Models\Association;
use Closure;

class AssociationContext
{
    private ?Association $association = null;

    public function set(?Association $association): void
    {
        $this->association = $association;
    }

    public function association(): ?Association
    {
        return $this->association;
    }

    public function id(): ?int
    {
        return $this->association?->getKey();
    }

    public function has(): bool
    {
        return $this->association !== null;
    }

    public function require(): Association
    {
        return $this->association ?? throw new MissingAssociationContext(
            'No association context has been resolved for the current request.'
        );
    }

    /**
     * Run a callback against an explicit association context and restore the
     * previous context afterwards. Used by seeders, tests and background jobs.
     */
    public function run(Association $association, Closure $callback): mixed
    {
        $previous = $this->association;
        $this->association = $association;

        try {
            return $callback($association);
        } finally {
            $this->association = $previous;
        }
    }

    public function clear(): void
    {
        $this->association = null;
    }
}
