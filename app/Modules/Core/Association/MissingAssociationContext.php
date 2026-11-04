<?php

namespace App\Modules\Core\Association;

use RuntimeException;

class MissingAssociationContext extends RuntimeException
{
    public static function forQuery(string $model): self
    {
        return new self(
            "Attempted to query [{$model}] without a resolved association context."
        );
    }
}
