<?php

namespace App\Providers;

use App\Services\S3Service;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Services\S3ServiceInterface;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            S3ServiceInterface::class,
            S3Service::class,
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
