<?php

namespace App\Policies;

use App\Models\FiscalPeriod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FiscalPeriodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins, managers, or department heads can view all fiscal periods
        return $user->hasRole(['administrator', 'superadmin']);
        return $user->role === 'super_admin' || $user->role === 'branch_admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FiscalPeriod $fiscalPeriod): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FiscalPeriod $fiscalPeriod): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FiscalPeriod $fiscalPeriod): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FiscalPeriod $fiscalPeriod): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FiscalPeriod $fiscalPeriod): bool
    {
        return $user->hasRole(['administrator', 'superadmin']);
    }
}
