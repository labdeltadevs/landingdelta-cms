<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool { return $user->can('view content'); }
    public function view(User $user, Branch $branch): bool { return $user->can('view content'); }
    public function create(User $user): bool { return $user->can('create content'); }
    public function update(User $user, Branch $branch): bool { return $user->can('update content'); }
    public function delete(User $user, Branch $branch): bool { return $user->can('delete content'); }
}
