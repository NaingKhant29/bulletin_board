<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot():void
    {
        // Customizing the password reset link
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'https://your-domain.com/reset-password?token=' . $token;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register():void
    {
        //
    }
}
