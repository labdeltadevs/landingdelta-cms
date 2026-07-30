<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.branches');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->can('view.branches');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.branches');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->can('edit.branches');
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->can('edit.branches');
    }
}
