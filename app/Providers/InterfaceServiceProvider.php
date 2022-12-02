<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\{
    S3Service,
    UserService
};
use App\Interfaces\Services\{
    S3ServiceInterface,
    UserServiceInterface
};

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        # Find better way to bind interfaces.

        $this->app->bind(
            S3ServiceInterface::class,
            S3Service::class,
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserService::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
