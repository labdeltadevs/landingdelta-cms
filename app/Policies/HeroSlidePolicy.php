<?php

namespace App\Policies;

use App\Models\HeroSlide;
use App\Models\User;

class HeroSlidePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view.hero');
    }

    public function view(User $user, HeroSlide $heroSlide): bool
    {
        return $user->can('view.hero');
    }

    public function create(User $user): bool
    {
        return $user->can('edit.hero');
    }

    public function update(User $user, HeroSlide $heroSlide): bool
    {
        return $user->can('edit.hero');
    }

    public function delete(User $user, HeroSlide $heroSlide): bool
    {
        return $user->can('edit.hero');
    }
}
