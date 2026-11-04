<?php

namespace App\Modules\Core\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case AssociationAdmin = 'association_admin';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Platform administrator',
            self::AssociationAdmin => 'Association administrator',
            self::Staff => 'Staff',
        };
    }
}
