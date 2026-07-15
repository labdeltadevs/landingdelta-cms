<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view content');
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->can('view content');
    }

    public function create(User $user): bool
    {
        return $user->can('create content');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->can('update content');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->can('delete content');
    }
}
