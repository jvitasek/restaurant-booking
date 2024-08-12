<?php

declare(strict_types=1);

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    public const string ROLE_SUPERADMIN = 'superadmin';

    public const string ROLE_GUEST = 'guest';
}
