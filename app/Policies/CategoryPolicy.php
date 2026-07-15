<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool { return $user->can('view content'); }
    public function view(User $user, Category $category): bool { return $user->can('view content'); }
    public function create(User $user): bool { return $user->can('create content'); }
    public function update(User $user, Category $category): bool { return $user->can('update content'); }
    public function delete(User $user, Category $category): bool { return $user->can('delete content'); }
}
