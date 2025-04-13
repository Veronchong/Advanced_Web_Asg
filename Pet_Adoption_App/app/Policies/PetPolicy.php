<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pet;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Pet $pet)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, Pet $pet)
    {
        return $user->isAdmin() || ($user->isStaff() && $pet->user_id === $user->id);
    }

    public function delete(User $user, Pet $pet)
    {
        return $user->isAdmin();
    }
}