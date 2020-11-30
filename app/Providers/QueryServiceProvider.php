<?php

namespace App\Providers;



use App\QueryService\User\GetEntireDataServiceInterface;
use App\QueryService\User\GetRecentServiceInterface;
use App\QueryService\User\MySQLGetEntireDataService;
use App\QueryService\User\MySQLGetRecentService;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            GetEntireDataServiceInterface::class,
            MySQLGetEntireDataService::class
        );
        $this->app->bind(
            GetRecentServiceInterface::class,
            MySQLGetRecentService::class
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
