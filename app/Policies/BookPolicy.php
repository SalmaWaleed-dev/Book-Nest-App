<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    // Every authenticated user (admin or not) may read books.
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, Book $book): bool { return true; }

    // Only admins may write.
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Book $book): bool { return $user->isAdmin(); }
    public function delete(User $user, Book $book): bool { return $user->isAdmin(); }
}
