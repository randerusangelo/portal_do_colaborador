<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        Gate::define('menu-admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('menu-dev', function ($user) {
            return $user->is_dev;
        });

        Gate::define('menu-gerdp', function ($user) {
            return $user->is_gerdp;
        });

        Gate::define('menu-juridico', function ($user) {
            return $user->is_juridico;
        });

        Gate::define('menu-dp', function ($user) {
            return $user->is_dp;
        });

        Gate::define('menu-anacredito', function ($user) {
            return $user->is_anacredito;
        });

        Gate::define('dev', function ($user) {
            return ( $user->matricula == 11165 );
        });

        /**
         * Regra que verifica se o usuário é o dono do objeto a ser alterado
         */
        Gate::define('OWNER', function(User $user, $object){
            return $user->matricula === $object->matricula;
        });

    }
}
