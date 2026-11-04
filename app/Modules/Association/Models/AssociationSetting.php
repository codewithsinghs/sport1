<?php

namespace App\Modules\Association\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssociationSetting extends Model
{
    protected $fillable = [
        'association_id',
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }
}
