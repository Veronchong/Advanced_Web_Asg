<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;

class AdoptionRequestPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, AdoptionRequest $request)
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function delete(User $user, AdoptionRequest $request)
    {
        return $user->isAdmin() || $user->isStaff();
    }
}