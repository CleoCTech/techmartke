<?php

namespace App\Policies;

use App\Models\User;

class DepartmentHeadPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if the user can access department leadership.
     */
    public function access(User $user)
    {
        // Check if the user has the required roles or permissions to access department leadership
        return $user->hasRole(['administrator', 'superadmin']) || $user->hasPermission(['accessdepartmentleadership']);
    }
}
