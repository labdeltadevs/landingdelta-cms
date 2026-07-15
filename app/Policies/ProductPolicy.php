<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool { return $user->can('view content'); }
    public function view(User $user, Product $product): bool { return $user->can('view content'); }
    public function create(User $user): bool { return $user->can('create content'); }
    public function update(User $user, Product $product): bool { return $user->can('update content'); }
    public function delete(User $user, Product $product): bool { return $user->can('delete content'); }
}
