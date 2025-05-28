<?php

namespace App\Policies;

use App\Models\FiscalYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FiscalYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins and managers can view all fiscal years
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FiscalYear $fiscalYear): bool
    {
         // Only admins, managers, or users who created the fiscal year can view it
         return $user->hasRole(['administrator', 'superadmin']);
         return $user->hasRole(['admin', 'manager']) || $fiscalYear->created_by === $user->email;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       // Only admins can create fiscal years
       return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FiscalYear $fiscalYear): bool
    {
        // Only admins or the user who created the fiscal year can update it
        return $user->hasRole(['administrator', 'superadmin']);
        return $user->hasRole('admin') || $fiscalYear->created_by === $user->email;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FiscalYear $fiscalYear): bool
    {
         // Only admins can delete fiscal years
         return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FiscalYear $fiscalYear): bool
    {
        // Only admins can restore deleted fiscal years
        return $user->hasRole(['administrator', 'superadmin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FiscalYear $fiscalYear): bool
    {
        // Only admins can permanently delete fiscal years
        return $user->hasRole(['administrator', 'superadmin']);
    }
}
