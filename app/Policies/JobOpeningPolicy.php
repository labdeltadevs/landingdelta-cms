<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;

class JobOpeningPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view content');
    }

    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('view content');
    }

    public function create(User $user): bool
    {
        return $user->can('manage news');
    }

    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('manage news');
    }

    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('manage news');
    }
}
