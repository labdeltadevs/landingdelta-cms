<?php

namespace App\Policies;

use App\Models\Brochure;
use App\Models\User;

class BrochurePolicy
{
    public function viewAny(User $user): bool { return $user->can('view content'); }
    public function view(User $user, Brochure $brochure): bool { return $user->can('view content'); }
    public function create(User $user): bool { return $user->can('manage brochures'); }
    public function update(User $user, Brochure $brochure): bool { return $user->can('manage brochures'); }
    public function delete(User $user, Brochure $brochure): bool { return $user->can('manage brochures'); }
}
