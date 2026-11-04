<?php

namespace App\Modules\Core\Models\Concerns;

use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Core\Association\MissingAssociationContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToAssociation
{
    public static function bootBelongsToAssociation(): void
    {
        static::addGlobalScope('association', function (Builder $builder): void {
            $association = app(AssociationContext::class)->association();

            if ($association === null) {
                throw MissingAssociationContext::forQuery(static::class);
            }

            $builder->where(
                $builder->getModel()->qualifyColumn('association_id'),
                $association->getKey()
            );
        });

        static::creating(function (Model $model): void {
            if ($model->getAttribute('association_id') !== null) {
                return;
            }

            $association = app(AssociationContext::class)->association();

            if ($association === null) {
                throw MissingAssociationContext::forQuery(static::class);
            }

            $model->setAttribute('association_id', $association->getKey());
        });
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }
}
