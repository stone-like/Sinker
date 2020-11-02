<?php

namespace App\Providers;

use App\Repository\CategoryRepository;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\QuestionRepository;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\TagRepository;
use App\Repository\TagRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            TagRepositoryInterface::class,
            TagRepository::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
        $this->app->bind(
           QuestionRepositoryInterface::class,
           QuestionRepository::class
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
