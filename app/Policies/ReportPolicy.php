<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('reports: menu');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $report): bool
    {
        return $user->can('reports: read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('reports: create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $report): bool
    {
        return $user->can('reports: update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $report): bool
    {
        return $user->can('reports: delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, $report): bool
    {
        return $user->can('reports: update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, $report): bool
    {
        return $user->can('reports: delete');
    }
}
