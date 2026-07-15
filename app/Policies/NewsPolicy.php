<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool { return $user->can('view content'); }
    public function view(User $user, News $news): bool { return $user->can('view content'); }
    public function create(User $user): bool { return $user->can('manage news'); }
    public function update(User $user, News $news): bool { return $user->can('manage news'); }
    public function delete(User $user, News $news): bool { return $user->can('manage news'); }
}
