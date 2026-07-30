<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.news');
    }

    public function view(User $user, News $news): bool
    {
        return $user->can('view.news');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.news');
    }

    public function update(User $user, News $news): bool
    {
        return $user->can('edit.news');
    }

    public function delete(User $user, News $news): bool
    {
        return $user->can('edit.news');
    }
}
