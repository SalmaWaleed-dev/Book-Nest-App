<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Only admins manage the user list.
    public function viewAny(User $user): bool { return $user->isAdmin(); }
    public function view(User $user, User $target): bool { return $user->isAdmin() || $user->id === $target->id; }
    public function create(User $user): bool { return $user->isAdmin(); }

    // A user may update their own profile; only an admin may update anyone (incl. role changes).
    public function update(User $user, User $target): bool
    {
        return $user->isAdmin() || $user->id === $target->id;
    }

    // Only an admin may change roles or delete accounts, and never their own account
    // (avoids a lockout with zero admins left).
    public function delete(User $user, User $target): bool
    {
        return $user->isAdmin() && $user->id !== $target->id;
    }

    public function changeRole(User $user, User $target): bool
    {
        return $user->isAdmin() && $user->id !== $target->id;
    }
}
