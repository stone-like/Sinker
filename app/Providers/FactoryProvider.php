<?php

namespace App\Providers;

use App\Factory\CreateTagUseCaseFactory;
use App\Factory\CreateTagUseCaseFactoryInterface;
use Illuminate\Support\ServiceProvider;

class FactoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
           CreateTagUseCaseFactoryInterface::class,
            CreateTagUseCaseFactory::class
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
