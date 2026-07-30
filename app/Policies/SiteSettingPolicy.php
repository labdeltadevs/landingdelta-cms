<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.settings');
    }

    public function view(User $user, SiteSetting $siteSetting): bool
    {
        return $user->can('view.settings');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.settings');
    }

    public function update(User $user, SiteSetting $siteSetting): bool
    {
        return $user->can('edit.settings');
    }

    public function delete(User $user, SiteSetting $siteSetting): bool
    {
        return $user->can('edit.settings');
    }
}
