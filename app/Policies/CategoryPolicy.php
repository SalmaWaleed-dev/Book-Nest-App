<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, Category $category): bool { return true; }

    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Category $category): bool { return $user->isAdmin(); }

    // Deletion must not break FK integrity: books.category_id is nullOnDelete,
    // so this is safe at the DB level, but we still gate who may trigger it.
    public function delete(User $user, Category $category): bool { return $user->isAdmin(); }
}
