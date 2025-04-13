<?php

namespace App\Providers;

use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Policies\PetPolicy;
use App\Policies\AdoptionRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pet::class => PetPolicy::class,
        AdoptionRequest::class => AdoptionRequestPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->isAdmin(); 
        });

        Gate::define('isStaff', function ($user) {
            return $user->isStaff(); 
        });

        // Pet Gates
        Gate::define('create', function ($user) {
            return $user->isAdmin() || $user->isStaff();
        });

        Gate::define('update', function ($user, Pet $pet) {
            return $user->isAdmin() || ($user->isStaff() && $pet->user_id === $user->id);
        });

        Gate::define('delete', function ($user, Pet $pet) {
            return $user->isAdmin();
        });

        // Adoption Request Gates
        Gate::define('viewAny', function ($user) {
            return $user->isAdmin() || $user->isStaff();
        });

        Gate::define('update', function ($user, AdoptionRequest $request) {
            return $user->isAdmin() || $user->isStaff();
        });

        Gate::define('delete', function ($user, AdoptionRequest $request) {
            return $user->isAdmin();
        });
    }
}