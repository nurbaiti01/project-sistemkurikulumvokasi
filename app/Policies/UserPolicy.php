<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends BasePolicy
{
    protected array $allowedRoles = [
        'Superadmin',
        'Akademik'
    ];
}
