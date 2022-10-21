<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\User;
use App\Models\OrderModel;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Passport::routes();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::personalAccessTokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));

        Gate::define('update-wallet', function (User $user, OrderModel $orderModel) {
            return $user->id === $orderModel->user_id;
        });
    }
}
