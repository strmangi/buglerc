<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth; 

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-user', function($user){
            return $user->hasAnyRole(['admin','super_admin']);
        });

        Gate::define('edit-user', function($user){
            return $user->hasRole('super_admin');
        });

        Gate::define('delete-user', function($user){
            return $user->hasRole('super_admin');
        });


        //get current login user detail
        view()->composer('*', function($view)
        {
        if (Auth::check()) {
            $view->with('currentUser', Auth::user());
        }else {
            $view->with('currentUser', null);
        }
    });
    }
}
