<?php

namespace App\Modules\Membership\Models;

use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use Illuminate\Database\Eloquent\Model;

class MembershipCategory extends Model
{
    use BelongsToAssociation;

    public const BILLING_PERIODS = [
        'one_time' => 'One time',
        'annual' => 'Annual',
        'lifetime' => 'Lifetime',
    ];

    protected $fillable = [
        'name',
        'slug',
        'fee',
        'billing_period',
        'requires_approval',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fee' => 'decimal:2',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function billingPeriodLabel(): string
    {
        return self::BILLING_PERIODS[$this->billing_period] ?? 'Annual';
    }

    public function feeLabel(): string
    {
        $amount = number_format((float) $this->fee, 2);

        return match ($this->billing_period) {
            'one_time' => "₹{$amount} one time",
            'lifetime' => "₹{$amount} lifetime",
            default => "₹{$amount} / year",
        };
    }
}
