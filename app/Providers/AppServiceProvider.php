<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return "http://localhost:3000/resetar/{$token}/?email={$notifiable->getEmailForPasswordReset()}"; # TODO finish method
        });
    }
}
