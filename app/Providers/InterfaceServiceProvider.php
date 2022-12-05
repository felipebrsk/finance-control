<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\{
    CurrencyRepositoryInterface,
    EarningRepositoryInterface,
    RecurringRepositoryInterface,
    SpendingRepositoryInterface,
    UserRepositoryInterface
};
use App\Services\{
    CurrencyService,
    EarningService,
    RecurringService,
    S3Service,
    SpendingService,
    UserService
};
use App\Contracts\Services\{
    CurrencyServiceInterface,
    EarningServiceInterface,
    RecurringServiceInterface,
    S3ServiceInterface,
    SpendingServiceInterface,
    UserServiceInterface
};
use App\Repositories\{
    CurrencyRepository,
    EarningRepository,
    RecurringRepository,
    SpendingRepository,
    UserRepository
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
        $this->app->bind(S3ServiceInterface::class, S3Service::class);

        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(RecurringRepositoryInterface::class, RecurringRepository::class);
        $this->app->bind(RecurringServiceInterface::class, RecurringService::class);

        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);

        $this->app->bind(EarningRepositoryInterface::class, EarningRepository::class);
        $this->app->bind(EarningServiceInterface::class, EarningService::class);

        $this->app->bind(SpendingRepositoryInterface::class, SpendingRepository::class);
        $this->app->bind(SpendingServiceInterface::class, SpendingService::class);
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
