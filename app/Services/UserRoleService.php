<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserRoleService
{
    /**
     * Check if the authenticated user has a specific role.
     */
    public static function hasRole($roles)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false; // No user is authenticated
        }

        $user->loadMissing('roles'); // Ensure roles are loaded
        
        return $user->hasRole($roles);
    }

    /**
     * Check if the authenticated user has a specific permission.
     */
    public static function hasPermission($permissions)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false; // No user is authenticated
        }

        $user->loadMissing('permissions'); // Ensure permissions are loaded
        
        return $user->hasPermission($permissions);
    }
}
