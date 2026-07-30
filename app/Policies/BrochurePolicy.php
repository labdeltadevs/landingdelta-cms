<?php

namespace App\Policies;

use App\Models\Brochure;
use App\Models\User;

class BrochurePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.brochures');
    }

    public function view(User $user, Brochure $brochure): bool
    {
        return $user->can('view.brochures');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.brochures');
    }

    public function update(User $user, Brochure $brochure): bool
    {
        return $user->can('edit.brochures');
    }

    public function delete(User $user, Brochure $brochure): bool
    {
        return $user->can('edit.brochures');
    }
}
