<?php

namespace App\Providers;

use App\Models\User;
use
Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\StorePolicy;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-trash' ,function($user){
            return $user->role === 'admin';
        });
    }
}
