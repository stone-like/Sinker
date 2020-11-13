<?php

namespace App\Providers;



use App\QueryService\User\GetEntireDataServiceInterface;
use App\QueryService\User\MySQLGetEntireDataService;
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
