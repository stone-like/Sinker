<?php

namespace Tests;

use App\Repository\CategoryRepositoryInterface;
use App\Repository\TagRepositoryInterface;
use App\Repository\TestTagRepository;
use App\UseCase\CreateCategoryUseCase;
use App\UseCase\CreateTagsUseCase;
use App\User;
use DatabaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $tagRepo;
    protected $cateRepo;
    protected $tagUseCase;
    protected $cateUseCase;

    public function setUp(): void
    {
        parent::setUp();



        $this->tagRepo = $this->app->make(TagRepositoryInterface::class);
        $this->cateRepo = $this->app->make(CategoryRepositoryInterface::class);
        $this->tagUseCase = $this->app->make(CreateTagsUseCase::class);
        $this->cateUseCase = $this->app->make(CreateCategoryUseCase::class);


    }



    protected function signIn($user = null)
    {
        $user = $user ?: factory(User::class)->create();
        $this->actingAs($user);
        return $user;
    }
}
