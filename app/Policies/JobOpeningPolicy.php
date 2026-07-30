<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;

class JobOpeningPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.job-openings');
    }

    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('view.job-openings');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.job-openings');
    }

    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('edit.job-openings');
    }

    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('edit.job-openings');
    }
}
